import os
import requests
from tqdm import tqdm
from datetime import datetime

LIMIT_SUBMISSIONS = 50  # จำกัดการส่งไม่เกิน 50 ครั้งต่อวัน

def download_file(url, local_filename):
    """ดาวน์โหลดไฟล์จาก URL และบันทึกเป็นไฟล์ในเครื่อง พร้อมแสดง Progress Bar"""
    with requests.get(url, stream=True) as r:
        r.raise_for_status()
        total_size = int(r.headers.get('content-length', 0))
        chunk_size = 8192  # ขนาดของแต่ละ chunk (8 KB)

        with tqdm(total=total_size, unit='iB', unit_scale=True, desc=local_filename) as pbar:
            with open(local_filename, 'wb') as f:
                for chunk in r.iter_content(chunk_size=chunk_size):
                    if chunk:
                        f.write(chunk)
                        pbar.update(len(chunk))

def save_submission_count(student_id):
    """บันทึกจำนวนครั้งที่นักศึกษาส่งงานในแต่ละวัน"""
    today = datetime.now().strftime('%Y-%m-%d')
    filename = f'submissions_{student_id}.txt'

    if not os.path.exists(filename):
        with open(filename, 'w') as f:
            f.write(f"{today},1\n")  # ส่งครั้งแรกในวันนี้
        return 1

    with open(filename, 'r+') as f:
        lines = f.readlines()
        if lines and lines[-1].startswith(today):
            count = int(lines[-1].split(',')[1]) + 1
            if count > LIMIT_SUBMISSIONS:
                return -1  # เกินลิมิต
            lines[-1] = f"{today},{count}\n"
        else:
            count = 1
            lines.append(f"{today},{count}\n")
        
        f.seek(0)
        f.writelines(lines)
        return count

def main():
    # รับรหัสนักศึกษา
    student_id = input("กรุณากรอกรหัสนักศึกษา: ").strip()
    
    # เช็คจำนวนครั้งที่ส่งในวันนี้
    submission_count = save_submission_count(student_id)
    if submission_count == -1:
        print(f"คุณส่งงานเกินลิมิต {LIMIT_SUBMISSIONS} ครั้งในวันนี้")
        return
    
    print(f"คุณส่งงานครั้งที่ {submission_count} ในวันนี้")
    
    subject = 'ENGCC304'

    # URL ที่จะใช้เพื่อดึงข้อมูลไฟล์พร้อมส่งตัวแปร subject
    list_files_url = f'https://thailandfxwarrior.com/lab/student_check_lab_manual.php?subject={subject}&student_id={student_id}'
    
    # โฟลเดอร์เป้าหมายตามวิชาที่เลือก
    target_folder = os.path.join('student_code', subject, student_id)

    if not os.path.exists(target_folder):
        os.makedirs(target_folder)

    response = requests.get(list_files_url)
    response.raise_for_status()

    try:
        file_list = response.json()
    except ValueError:
        print(f"Error: Response is not valid JSON. Received: {response.text}")
        return

    # ตรวจสอบว่า file_list เป็นลิสต์หรือไม่ หรือเป็น dict (เช่น {'error': '...'})
    if isinstance(file_list, dict):
        print(f"Error: {file_list.get('error', 'Unexpected response format.')}")
        return
    elif not isinstance(file_list, list):
        print(f"Error: Expected a list of URLs but got {type(file_list).__name__}. Response: {file_list}")
        return

    for file_url in file_list:
        # ตรวจสอบว่า URL มี schema (http หรือ https) หรือไม่
        if not file_url.startswith(('http://', 'https://')):
            print(f"Invalid URL: {file_url}. Skipping this file.")
            continue

        file_name = file_url.split('/')[-1]
        file_path = os.path.join(target_folder, file_name)
        print(f"Downloading {file_url} to {file_path}")
        download_file(file_url, file_path)

    print(f"All files downloaded to {target_folder}")

if __name__ == "__main__":
    main()
