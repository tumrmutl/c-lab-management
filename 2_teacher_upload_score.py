import os
import glob
import requests
import csv
import json
import pyperclip  # สำหรับการคัดลอกข้อมูลไปยัง clipboard
from tqdm import tqdm  # สำหรับ Progress Bar

result_folder = 'result/'

def copy_to_clipboard(data):
    """Copy data to clipboard."""
    try:
        pyperclip.copy(data)
    except Exception as e:
        print(f"Failed to copy data to clipboard: {e}")

def upload_data(data, upload_url):
    """Send JSON data to the server."""
    try:
        headers = {'Content-Type': 'application/json'}
        response = requests.post(upload_url, data=json.dumps(data), headers=headers)
        response.raise_for_status()  # ตรวจสอบว่า request สำเร็จ
        response_data = response.json()

        if response_data['status'] != 'success':
            print(f"Upload failed: {response_data.get('message', 'Upload failed')}")
    except requests.RequestException as e:
        print(f"Request failed: {e}")

def process_csv_file(csv_file, upload_url):
    """Process CSV file and convert it to a JSON format."""
    try:
        # เพิ่ม encoding='utf-8' ในการเปิดไฟล์
        with open(csv_file, 'r', encoding='utf-8') as file:
            reader = csv.DictReader(file)
            data = list(reader)  # Convert CSV rows to a list of dictionaries

        # Convert to JSON format
        json_data = json.dumps(data, indent=4)

        # Copy data to clipboard
        copy_to_clipboard(json_data)
        
        # Upload JSON data
        upload_data(data, upload_url)

    except IOError as e:
        print(f"Failed to open file: {e}")


def get_subject_code():
    """Read the subject code from subject.dat file."""
    try:
        with open('subject.dat', 'r') as file:
            subject_code = file.read().strip()
            return subject_code
    except IOError as e:
        print(f"Failed to read subject code: {e}")
        return None

def main():
    # Get the subject code from subject.dat
    subject_code = get_subject_code()
    if not subject_code:
        print("Subject code not found. Please check the subject.dat file.")
        return

    # Construct the upload URL with the subject code
    upload_url = f'https://thailandfxwarrior.com/lab/upload_csv.php?subject={subject_code}'

    # Correct the pattern to match the actual file names
    pattern = os.path.join(result_folder, f'{subject_code}/results_lab*.csv')
    csv_files = glob.glob(pattern)
    
    if not csv_files:
        print(f"No CSV files found in folder '{result_folder}/{subject_code}' with pattern '{pattern}'.")
        return

    # Initialize the Progress Bar
    with tqdm(total=100, desc="Processing files", unit="%", ncols=80) as pbar:
        num_files = len(csv_files)
        step = 100 / num_files if num_files else 100
        
        for i, csv_file in enumerate(csv_files):
            process_csv_file(csv_file, upload_url)
            # Update the Progress Bar based on percentage completion
            pbar.update(step)

if __name__ == "__main__":
    main()
