import os
import difflib
import csv
import hashlib
import re
import tokenize
from sklearn.feature_extraction.text import CountVectorizer
from io import StringIO
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from transformers import RobertaTokenizer, RobertaModel
import torch
from tqdm import tqdm

# Read subject code from subject.dat
def read_subject_code(file_path):
    try:
        with open(file_path, 'r') as file:
            subject_code = file.read().strip()
            return subject_code
    except IOError as e:
        print(f"Failed to read subject code from file: {e}")
        return ''

# Define folder paths
subject_code = read_subject_code('subject.dat')
if not subject_code:
    print("Subject code not found. Exiting.")
    exit()

folder_path = f'student_code/{subject_code}'
result_folder = f'result_duplicate/{subject_code}'

# Create result folder if it doesn't exist
os.makedirs(result_folder, exist_ok=True)

files = [f for f in os.listdir(folder_path) if f.endswith('.c')]

# Function to read file content
def read_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        return file.read()

# Function to hash file content
def hash_file(file_path):
    hasher = hashlib.md5()
    with open(file_path, 'rb') as file:
        for chunk in iter(lambda: file.read(8192), b""):
            hasher.update(chunk)
    return hasher.hexdigest()

# Function to normalize code by removing comments and extra whitespace
def normalize_code(code):
    code = re.sub(r'//.*|/\*.*?\*/', '', code, flags=re.DOTALL)
    code = re.sub(r'\s+', ' ', code).strip()
    return code

def sanitize_code_for_tokenization(code):
    """
    Sanitize code by ensuring that strings and comments are properly closed
    to avoid tokenize.TokenError.
    """
    # Remove comments
    code = re.sub(r'//.*|/\*.*?\*/', '', code, flags=re.DOTALL)
    
    # Ensure quotes are balanced
    if code.count('"') % 2 != 0 or code.count("'") % 2 != 0:
        raise ValueError("Unmatched quotes in the code")

    return code


# def sanitize_code_for_tokenization(code):
#     """
#     Sanitize code by ensuring that strings and comments are properly closed
#     to avoid tokenize.TokenError.
#     """
#     # Remove comments
#     code = re.sub(r'//.*|/\*.*?\*/', '', code, flags=re.DOTALL)
    
#     # Ensure quotes are balanced
#     if code.count('"') % 2 != 0 or code.count("'") % 2 != 0:
#         raise ValueError("Unmatched quotes in the code")

#     return code

def compare_tokenization(code1, code2):
    try:
        code1 = sanitize_code_for_tokenization(code1)
        code2 = sanitize_code_for_tokenization(code2)
        
        tokens1 = [token.string for token in tokenize.generate_tokens(StringIO(code1).readline) if token.type in (tokenize.NAME, tokenize.NUMBER)]
        tokens2 = [token.string for token in tokenize.generate_tokens(StringIO(code2).readline) if token.type in (tokenize.NAME, tokenize.NUMBER)]

        if not tokens1 or not tokens2:
            print("Token lists are empty for one or both files.")
            return 0

        vectorizer = CountVectorizer().fit_transform([' '.join(tokens1), ' '.join(tokens2)])
        return cosine_similarity(vectorizer.toarray())[0, 1] * 100
    except tokenize.TokenError as e:
        print(f"Tokenization error: {e}")
        return 0



# Function to compare tokenization
# def compare_tokenization(code1, code2):
#     tokens1 = [token.string for token in tokenize.generate_tokens(StringIO(code1).readline) if token.type in (tokenize.NAME, tokenize.NUMBER)]
#     tokens2 = [token.string for token in tokenize.generate_tokens(StringIO(code2).readline) if token.type in (tokenize.NAME, tokenize.NUMBER)]
#     vectorizer = CountVectorizer().fit_transform([' '.join(tokens1), ' '.join(tokens2)])
#     return cosine_similarity(vectorizer.toarray())[0, 1] * 100

# Function to get embeddings and compare them
tokenizer = RobertaTokenizer.from_pretrained('microsoft/codebert-base')
model = RobertaModel.from_pretrained('microsoft/codebert-base')

def get_code_embedding(code):
    inputs = tokenizer(code, return_tensors='pt', truncation=True, max_length=512)
    with torch.no_grad():
        outputs = model(**inputs)
    return outputs.last_hidden_state.mean(dim=1).numpy()

def compare_embeddings(code1, code2):
    return cosine_similarity(get_code_embedding(code1), get_code_embedding(code2))[0, 0] * 100

# Function to compare TF-IDF vectors
def compare_tfidf(code1, code2):
    vectorizer = TfidfVectorizer()
    vectors = vectorizer.fit_transform([code1, code2])
    return cosine_similarity(vectors)[0, 1] * 100

# Function to group files by lab ID
def group_files_by_lab(files):
    lab_groups = {}
    for file in files:
        parts = file.split('_')
        lab_id = parts[1].split('.')[0]
        lab_groups.setdefault(lab_id, []).append(file)
    return lab_groups

# Function to compare files
def compare_files(file_path1, file_path2):
    code1, code2 = read_file(file_path1), read_file(file_path2)
    normalized_content1, normalized_content2 = normalize_code(code1), normalize_code(code2)
    
    return {
        'similarity': difflib.SequenceMatcher(None, normalized_content1, normalized_content2).ratio() * 100,
        'hash_similarity': 100 if hash_file(file_path1) == hash_file(file_path2) else 0,
        'structure_similarity': difflib.SequenceMatcher(None, re.sub(r'\b\w+\b', '', normalized_content1), re.sub(r'\b\w+\b', '', normalized_content2)).ratio() * 100,
        'token_similarity': compare_tokenization(code1, code2),
        'embedding_similarity': compare_embeddings(code1, code2),
        'tfidf_similarity': compare_tfidf(code1, code2)
    }

# Function to compare files in groups
def compare_files_in_groups(lab_groups):
    similarities = []
    
    for lab_id, file_list in tqdm(lab_groups.items(), desc="Processing labs"):
        num_files = len(file_list)
        for i in range(num_files):
            for j in range(i + 1, num_files):
                file1_path, file2_path = os.path.join(folder_path, file_list[i]), os.path.join(folder_path, file_list[j])
                file1_name = file_list[i].split('_')[0]  # Extracting the base file name
                file2_name = file_list[j].split('_')[0]  # Extracting the base file name
                similarities.append({
                    'file1': file1_name,
                    'file2': file2_name,
                    'lab_id': lab_id,
                    **compare_files(file1_path, file2_path)
                })
    
    return similarities

# Group files by lab and compare
lab_groups = group_files_by_lab(files)
similarities = compare_files_in_groups(lab_groups)

# Save results to CSV
csv_file_path = os.path.join(result_folder, 'result_duplicate.csv')
with open(csv_file_path, mode='w', newline='', encoding='utf-8') as file:
    writer = csv.writer(file)
    writer.writerow(['Lab ID', 'File 1', 'File 2', 'Similarity (%)', 'Hash Similarity (%)', 'Structural Similarity (%)', 'Token Similarity (%)', 'Embedding Similarity (%)', 'TF-IDF Similarity (%)'])
    writer.writerows([
        [result['lab_id'], result['file1'], result['file2'], f"{result['similarity']:.2f}", f"{result['hash_similarity']:.2f}", f"{result['structure_similarity']:.2f}", f"{result['token_similarity']:.2f}", f"{result['embedding_similarity']:.2f}", f"{result['tfidf_similarity']:.2f}"]
        for result in similarities
    ])

print(f"Results have been written to {csv_file_path}")
