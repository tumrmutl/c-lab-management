import os
import difflib
import csv
import hashlib
import re
import ast
import tokenize
from io import StringIO
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.metrics.pairwise import cosine_similarity

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
    # ลบคอมเมนต์
    code = re.sub(r'//.*|/\*.*?\*/', '', code, flags=re.DOTALL)
    # ลบช่องว่างที่ไม่จำเป็น
    code = re.sub(r'\s+', ' ', code)
    # ลบบรรทัดว่าง
    code = re.sub(r'\n\s*\n', '\n', code)
    return code.strip()

# ฟังก์ชันทำความสะอาดโค้ดสำหรับเปรียบเทียบโครงสร้างที่ดียิ่งขึ้น
def normalize_code_structure(code):
    # ลบคอมเมนต์
    code = re.sub(r'//.*|/\*.*?\*/', '', code, flags=re.DOTALL)
    # ลบช่องว่างที่ไม่จำเป็น
    code = re.sub(r'\s+', ' ', code)
    # ลบชื่อฟังก์ชัน, ตัวแปร, คอมเมนต์ภายในโค้ด
    code = re.sub(r'\b\w+\b', '', code)
    # ลบคำสั่งที่ไม่เกี่ยวข้อง
    code = re.sub(r'\breturn\b', '', code)
    return code.strip()

# ฟังก์ชันเปรียบเทียบโครงสร้าง (AST)
def compare_ast(file_path1, file_path2):
    code1 = read_file(file_path1)
    code2 = read_file(file_path2)
    try:
        tree1 = ast.parse(code1)
        tree2 = ast.parse(code2)
        return difflib.SequenceMatcher(None, ast.dump(tree1), ast.dump(tree2)).ratio() * 100
    except SyntaxError:
        return 0

# ฟังก์ชันการเปรียบเทียบ Tokenization
def compare_tokenization(file_path1, file_path2):
    code1 = read_file(file_path1)
    code2 = read_file(file_path2)
    tokens1 = list(tokenize.generate_tokens(StringIO(code1).readline))
    tokens2 = list(tokenize.generate_tokens(StringIO(code2).readline))
    tokens1 = [token.string for token in tokens1 if token.type == tokenize.NAME or token.type == tokenize.NUMBER]
    tokens2 = [token.string for token in tokens2 if token.type == tokenize.NAME or token.type == tokenize.NUMBER]
    vectorizer = CountVectorizer().fit_transform([' '.join(tokens1), ' '.join(tokens2)])
    vectors = vectorizer.toarray()
    return cosine_similarity(vectors)[0, 1] * 100

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
    for lab_id, file_list in lab_groups.items():
        num_files = len(file_list)
        for i in range(num_files):
            for j in range(num_files):
                if i != j:
                    file1_path = os.path.join(folder_path, file_list[i])
                    file2_path = os.path.join(folder_path, file_list[j])
                    
                    file1_content = read_file(file1_path)
                    file2_content = read_file(file2_path)

                    # ทำความสะอาดโค้ดก่อนเปรียบเทียบ
                    normalized_content1 = normalize_code(file1_content)
                    normalized_content2 = normalize_code(file2_content)
                    
                    # ทำความสะอาดโค้ดสำหรับเปรียบเทียบโครงสร้าง
                    normalized_structure1 = normalize_code_structure(file1_content)
                    normalized_structure2 = normalize_code_structure(file2_content)

                    # เปรียบเทียบความเหมือนของเนื้อหาด้วย difflib
                    similarity = difflib.SequenceMatcher(None, normalized_content1, normalized_content2).ratio() * 100
                    
                    # เปรียบเทียบการแฮชของไฟล์
                    hash1 = hash_file(file1_path)
                    hash2 = hash_file(file2_path)
                    hash_similarity = 100 if hash1 == hash2 else 0

                    # เปรียบเทียบตามโครงสร้าง (ใช้ normalized structure content)
                    structure_similarity = difflib.SequenceMatcher(None, normalized_structure1, normalized_structure2).ratio() * 100
                    
                    # เปรียบเทียบ AST
                    ast_similarity = compare_ast(file1_path, file2_path)
                    
                    # เปรียบเทียบ Tokenization
                    token_similarity = compare_tokenization(file1_path, file2_path)
                    
                    similarities.append({
                        'file1': extract_file_id(file_list[i]),
                        'file2': extract_file_id(file_list[j]),
                        'lab_id': lab_id,
                        'similarity': similarity,
                        'hash_similarity': hash_similarity,
                        'structure_similarity': structure_similarity,
                        'ast_similarity': ast_similarity,
                        'token_similarity': token_similarity
                    })
    return similarities

# แยกไฟล์ตาม Lab
lab_groups = group_files_by_lab(files)

# เปรียบเทียบไฟล์ในแต่ละกลุ่ม
similarities = compare_files_in_groups(lab_groups)

# บันทึกผลลัพธ์ลงในไฟล์ CSV
csv_file_path = 'result_duplicate.csv'
with open(csv_file_path, mode='w', newline='', encoding='utf-8') as file:
    writer = csv.writer(file)
    writer.writerow(['Lab ID', 'File 1', 'File 2', 'Similarity (%)', 'Hash Similarity (%)', 'Structural Similarity (%)', 'AST Similarity (%)', 'Token Similarity (%)'])
    for result in similarities:
        writer.writerow([result['lab_id'], result['file1'], result['file2'], f"{result['similarity']:.2f}", f"{result['hash_similarity']:.2f}", f"{result['structure_similarity']:.2f}", f"{result['ast_similarity']:.2f}", f"{result['token_similarity']:.2f}"])

print(f"Results have been written to {csv_file_path}")
