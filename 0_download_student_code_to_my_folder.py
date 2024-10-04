import os
import requests
from tqdm import tqdm

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

def save_subject_to_file(subject):
    """บันทึกรหัสวิชาลงในไฟล์ subject.dat"""
    with open('subject.dat', 'w') as f:
        f.write(subject)

def main():
    # เลือกรหัสวิชาที่ต้องการตรวจ
    # subject = input("กรุณาเลือกรหัสวิชา (ENGCC304, ENGCE117, ENGCE174): ")

    # เมนูเลือกวิชา
    # print("กรุณาเลือกรหัสวิชา:")
    # print("1. ENGCC304")
    # print("2. ENGCE117")
    # print("3. ENGCE174")

    # choice = input("กรอกหมายเลขวิชา (1/2/3): ")
    # if choice == '1':
    #     subject = 'ENGCC304'
    # elif choice == '2':
    #     subject = 'ENGCE117'
    # elif choice == '3':
    #     subject = 'ENGCE174'
    # else:
    #     print("เลือกหมายเลขไม่ถูกต้อง กรุณาลองใหม่")
    #     return
    subject = 'ENGCC304'
    save_subject_to_file(subject)

    # URL ที่จะใช้เพื่อดึงข้อมูลไฟล์พร้อมส่งตัวแปร subject
    list_files_url = f'https://thailandfxwarrior.com/lab/show_student_lab.php?subject={subject}'
    
    # โฟลเดอร์เป้าหมายตามวิชาที่เลือก
    target_folder = os.path.join('student_code', subject)

    # ตรวจสอบและสร้างโฟลเดอร์ถ้ายังไม่มี
    if not os.path.exists(target_folder):
        os.makedirs(target_folder)

    # ดึงรายชื่อไฟล์จาก PHP โดยใช้รหัสวิชา
    response = requests.get(list_files_url)
    response.raise_for_status()

    # ตรวจสอบว่าการตอบกลับเป็น JSON หรือไม่
    try:
        file_list = response.json()
    except ValueError:
        print(f"Error: Response is not valid JSON. Received: {response.text}")
        return

    # ดาวน์โหลดไฟล์ทั้งหมด
    for file_url in file_list:
        file_name = file_url.split('/')[-1]
        file_path = os.path.join(target_folder, file_name)
        print(f"Downloading {file_url} to {file_path}")
        download_file(file_url, file_path)

    print(f"All files downloaded to {target_folder}")

if __name__ == "__main__":
    main()
