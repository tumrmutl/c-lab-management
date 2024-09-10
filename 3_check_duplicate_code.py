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
from tqdm import tqdm  # Import tqdm

# ดึงรายชื่อไฟล์จากโฟลเดอร์
folder_path = 'student_code'
files = [f for f in os.listdir(folder_path) if f.endswith('.c')]

# ฟังก์ชันสำหรับอ่านไฟล์
def read_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        return file.read()

# ฟังก์ชันในการสร้างแฮชของเนื้อหาของไฟล์
def hash_file(file_path):
    hasher = hashlib.md5()
    with open(file_path, 'rb') as file:
        for chunk in iter(lambda: file.read(8192), b""):
            hasher.update(chunk)
    return hasher.hexdigest()

# ฟังก์ชันทำความสะอาดโค้ด
def normalize_code(code):
    code = re.sub(r'//.*|/\*.*?\*/', '', code, flags=re.DOTALL)
    code = re.sub(r'\s+', ' ', code).strip()
    return code

# ฟังก์ชันเปรียบเทียบ Tokenization
def compare_tokenization(code1, code2):
    tokens1 = [token.string for token in tokenize.generate_tokens(StringIO(code1).readline) if token.type in (tokenize.NAME, tokenize.NUMBER)]
    tokens2 = [token.string for token in tokenize.generate_tokens(StringIO(code2).readline) if token.type in (tokenize.NAME, tokenize.NUMBER)]
    vectorizer = CountVectorizer().fit_transform([' '.join(tokens1), ' '.join(tokens2)])
    return cosine_similarity(vectorizer.toarray())[0, 1] * 100

# ฟังก์ชันเปรียบเทียบ Embeddings ด้วย CodeBERT
tokenizer = RobertaTokenizer.from_pretrained('microsoft/codebert-base')
model = RobertaModel.from_pretrained('microsoft/codebert-base')

def get_code_embedding(code):
    inputs = tokenizer(code, return_tensors='pt', truncation=True, max_length=512)
    with torch.no_grad():
        outputs = model(**inputs)
    return outputs.last_hidden_state.mean(dim=1).numpy()

def compare_embeddings(code1, code2):
    return cosine_similarity(get_code_embedding(code1), get_code_embedding(code2))[0, 0] * 100

# ฟังก์ชันการเปรียบเทียบ TF-IDF
def compare_tfidf(code1, code2):
    vectorizer = TfidfVectorizer()
    vectors = vectorizer.fit_transform([code1, code2])
    return cosine_similarity(vectors)[0, 1] * 100

# ฟังก์ชันเพื่อแยกไฟล์ตาม Lab
def group_files_by_lab(files):
    lab_groups = {}
    for file in files:
        parts = file.split('_')
        lab_id = parts[1].split('.')[0]  # แก้ไขเพื่อให้ได้ Lab ID ถูกต้อง
        lab_groups.setdefault(lab_id, []).append(file)
    return lab_groups

# ฟังก์ชันเปรียบเทียบไฟล์
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

# ฟังก์ชันเปรียบเทียบไฟล์ในกลุ่ม
def compare_files_in_groups(lab_groups):
    similarities = []
    
    # สร้างแถบความก้าวหน้าด้วย tqdm
    for lab_id, file_list in tqdm(lab_groups.items(), desc="Processing labs"):
        num_files = len(file_list)
        # ตรวจสอบการเปรียบเทียบคู่ไฟล์ทั้งหมดในแต่ละกลุ่ม
        for i in range(num_files):
            for j in range(i + 1, num_files):
                file1_path, file2_path = os.path.join(folder_path, file_list[i]), os.path.join(folder_path, file_list[j])
                similarities.append({
                    'file1': file_list[i],
                    'file2': file_list[j],
                    'lab_id': lab_id,
                    **compare_files(file1_path, file2_path)  # Corrected here
                })
    
    return similarities


# แยกไฟล์ตาม Lab และเปรียบเทียบ
lab_groups = group_files_by_lab(files)
similarities = compare_files_in_groups(lab_groups)

# บันทึกผลลัพธ์ลงในไฟล์ CSV
csv_file_path = 'result_duplicate.csv'
with open(csv_file_path, mode='w', newline='', encoding='utf-8') as file:
    writer = csv.writer(file)
    writer.writerow(['Lab ID', 'File 1', 'File 2', 'Similarity (%)', 'Hash Similarity (%)', 'Structural Similarity (%)', 'Token Similarity (%)', 'Embedding Similarity (%)', 'TF-IDF Similarity (%)'])
    writer.writerows([
        [result['lab_id'], result['file1'], result['file2'], f"{result['similarity']:.2f}", f"{result['hash_similarity']:.2f}", f"{result['structure_similarity']:.2f}", f"{result['token_similarity']:.2f}", f"{result['embedding_similarity']:.2f}", f"{result['tfidf_similarity']:.2f}"]
        for result in similarities
    ])

print(f"Results have been written to {csv_file_path}")
