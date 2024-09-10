import os
import difflib
import csv
import hashlib
import re
import tokenize
from io import StringIO
from sklearn.feature_extraction.text import CountVectorizer, TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from transformers import RobertaTokenizer, RobertaModel
import torch
import networkx as nx
import time

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
        while chunk := file.read(8192):
            hasher.update(chunk)
    return hasher.hexdigest()

# ฟังก์ชันทำความสะอาดโค้ด
def normalize_code(code):
    code = re.sub(r'//.*|/\*.*?\*/', '', code, flags=re.DOTALL)
    code = re.sub(r'\s+', ' ', code)
    code = re.sub(r'\n\s*\n', '\n', code)
    return code.strip()

# ฟังก์ชันทำความสะอาดโค้ดสำหรับเปรียบเทียบโครงสร้างที่ดียิ่งขึ้น
def normalize_code_structure(code):
    code = re.sub(r'//.*|/\*.*?\*/', '', code, flags=re.DOTALL)
    code = re.sub(r'\s+', ' ', code)
    code = re.sub(r'\b\w+\b', '', code)
    code = re.sub(r'\breturn\b', '', code)
    return code.strip()

# ฟังก์ชันเปรียบเทียบ Tokenization
def compare_tokenization(code1, code2):
    tokens1 = list(tokenize.generate_tokens(StringIO(code1).readline))
    tokens2 = list(tokenize.generate_tokens(StringIO(code2).readline))
    tokens1 = [token.string for token in tokens1 if token.type == tokenize.NAME or token.type == tokenize.NUMBER]
    tokens2 = [token.string for token in tokens2 if token.type == tokenize.NAME or token.type == tokenize.NUMBER]
    vectorizer = CountVectorizer().fit_transform([' '.join(tokens1), ' '.join(tokens2)])
    vectors = vectorizer.toarray()
    return cosine_similarity(vectors)[0, 1] * 100

# ฟังก์ชันเปรียบเทียบ Embeddings ด้วย CodeBERT
tokenizer = RobertaTokenizer.from_pretrained('microsoft/codebert-base')
model = RobertaModel.from_pretrained('microsoft/codebert-base')

def get_code_embedding(code):
    inputs = tokenizer(code, return_tensors='pt', truncation=True, max_length=512)
    with torch.no_grad():
        outputs = model(**inputs)
    return outputs.last_hidden_state.mean(dim=1).numpy()

def compare_embeddings(code1, code2):
    embedding1 = get_code_embedding(code1)
    embedding2 = get_code_embedding(code2)
    return cosine_similarity(embedding1, embedding2)[0, 0] * 100

# ฟังก์ชันการเปรียบเทียบ TF-IDF
def compare_tfidf(code1, code2):
    vectorizer = TfidfVectorizer()
    vectors = vectorizer.fit_transform([code1, code2])
    return cosine_similarity(vectors)[0, 1] * 100

# ฟังก์ชันการเปรียบเทียบกราฟ (ตัวอย่าง)
def code_to_graph(code):
    # Dummy implementation: convert code to a graph representation
    return nx.Graph()

def compare_graphs(file_path1, file_path2):
    code1 = read_file(file_path1)
    code2 = read_file(file_path2)
    graph1 = code_to_graph(code1)
    graph2 = code_to_graph(code2)
    
    # Check if either graph is empty
    if len(graph1.nodes()) == 0 or len(graph2.nodes()) == 0:
        return 0  # Return 0 similarity if either graph is empty

    # Calculate graph edit distance and normalize by the maximum number of nodes
    try:
        edit_distance = nx.graph_edit_distance(graph1, graph2)
        max_nodes = max(len(graph1.nodes()), len(graph2.nodes()))
        graph_similarity = (1 - edit_distance / max_nodes) * 100
    except ZeroDivisionError:
        graph_similarity = 0  # Return 0 similarity in case of an unexpected division by zero error

    return graph_similarity

# ฟังก์ชันเพื่อแยกไฟล์ตาม Lab
def group_files_by_lab(files):
    lab_groups = {}
    for file in files:
        parts = file.split('_')
        lab_id = parts[-1].split('.')[0]
        if lab_id not in lab_groups:
            lab_groups[lab_id] = []
        lab_groups[lab_id].append(file)
    return lab_groups

# ฟังก์ชันที่ตัดรหัสส่วนเกินออกจากชื่อไฟล์
def extract_file_id(file_name):
    return file_name.split('_')[0]

# ฟังก์ชันเปรียบเทียบไฟล์ตามโครงสร้าง
def compare_files_in_groups(lab_groups):
    similarities = []
    total_comparisons = sum(len(file_list) * (len(file_list) - 1) for file_list in lab_groups.values()) // 2
    comparisons_done = 0
    
    for lab_id, file_list in lab_groups.items():
        num_files = len(file_list)
        for i in range(num_files):
            for j in range(i + 1, num_files):
                file1_path = os.path.join(folder_path, file_list[i])
                file2_path = os.path.join(folder_path, file_list[j])
                
                similarity = compare_files(file1_path, file2_path)
                
                similarities.append({
                    'file1': extract_file_id(file_list[i]),
                    'file2': extract_file_id(file_list[j]),
                    'lab_id': lab_id,
                    **similarity
                })
                
                comparisons_done += 1
                progress = (comparisons_done / total_comparisons) * 100
                print(f"Progress: {progress:.2f}%")
                time.sleep(0.1)  # Small delay to simulate processing time
    
    return similarities

def compare_files(file_path1, file_path2):
    file1_content = read_file(file_path1)
    file2_content = read_file(file_path2)

    # ทำความสะอาดโค้ดก่อนเปรียบเทียบ
    normalized_content1 = normalize_code(file1_content)
    normalized_content2 = normalize_code(file2_content)
    
    # ทำความสะอาดโค้ดสำหรับเปรียบเทียบโครงสร้าง
    normalized_structure1 = normalize_code_structure(file1_content)
    normalized_structure2 = normalize_code_structure(file2_content)

    # เปรียบเทียบความเหมือนของเนื้อหาด้วย difflib
    similarity = difflib.SequenceMatcher(None, normalized_content1, normalized_content2).ratio() * 100
    
    # เปรียบเทียบการแฮชของไฟล์
    hash1 = hash_file(file_path1)
    hash2 = hash_file(file_path2)
    hash_similarity = 100 if hash1 == hash2 else 0

    # เปรียบเทียบตามโครงสร้าง (ใช้ normalized structure content)
    structure_similarity = difflib.SequenceMatcher(None, normalized_structure1, normalized_structure2).ratio() * 100
    
    # เปรียบเทียบ Tokenization
    token_similarity = compare_tokenization(file1_content, file2_content)
    
    # AI-based comparison methods
    embedding_similarity = compare_embeddings(file1_content, file2_content)
    tfidf_similarity = compare_tfidf(file1_content, file2_content)
    
    return {
        'similarity': similarity,
        'hash_similarity': hash_similarity,
        'structure_similarity': structure_similarity,
        'token_similarity': token_similarity,
        'embedding_similarity': embedding_similarity,
        'tfidf_similarity': tfidf_similarity
    }

# แยกไฟล์ตาม Lab
lab_groups = group_files_by_lab(files)

# เปรียบเทียบไฟล์ในแต่ละกลุ่ม
similarities = compare_files_in_groups(lab_groups)

# บันทึกผลลัพธ์ลงในไฟล์ CSV
csv_file_path = 'result_duplicate.csv'
with open(csv_file_path, mode='w', newline='', encoding='utf-8') as file:
    writer = csv.writer(file)
    writer.writerow(['Lab ID', 'File 1', 'File 2', 'Similarity (%)', 'Hash Similarity (%)', 'Structural Similarity (%)', 'Token Similarity (%)', 'Embedding Similarity (%)', 'TF-IDF Similarity (%)'])
    for result in similarities:
        writer.writerow([result['lab_id'], result['file1'], result['file2'], f"{result['similarity']:.2f}", f"{result['hash_similarity']:.2f}", f"{result['structure_similarity']:.2f}", f"{result['token_similarity']:.2f}", f"{result['embedding_similarity']:.2f}", f"{result['tfidf_similarity']:.2f}"])

print(f"Results have been written to {csv_file_path}")
