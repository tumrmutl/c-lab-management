import os
import requests

def download_file(url, local_filename):
    """ดาวน์โหลดไฟล์จาก URL และบันทึกเป็นไฟล์ในเครื่อง"""
    with requests.get(url, stream=True) as r:
        r.raise_for_status()
        with open(local_filename, 'wb') as f:
            for chunk in r.iter_content(chunk_size=8192):
                f.write(chunk)

def main():
    list_files_url = 'https://thailandfxwarrior.com/lab/show_student_lab.php'
    target_folder = 'student_code'

    if not os.path.exists(target_folder):
        os.makedirs(target_folder)

    # ดึงรายชื่อไฟล์จาก PHP
    response = requests.get(list_files_url)
    response.raise_for_status()
    file_list = response.json()

    # ดาวน์โหลดไฟล์ทั้งหมด
    for file_url in file_list:
        file_name = file_url.split('/')[-1]
        file_path = os.path.join(target_folder, file_name)
        print(f"Downloading {file_url} to {file_path}")
        download_file(file_url, file_path)

    print(f"All files downloaded to {target_folder}")

if __name__ == "__main__":
    main()
