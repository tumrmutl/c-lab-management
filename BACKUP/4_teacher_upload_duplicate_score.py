import csv
import json
import requests
import time
from tqdm import tqdm  # สำหรับแสดง progress bar

def upload_data(data, upload_url):
    """Send JSON data to the server."""
    try:
        headers = {'Content-Type': 'application/json'}

        # เริ่มการแสดง progress bar สำหรับการอัพโหลด
        with tqdm(total=100, desc="Uploading data") as pbar:
            response = requests.post(upload_url, data=json.dumps(data), headers=headers)
            response.raise_for_status()  # ตรวจสอบว่า request สำเร็จ
            response_data = response.json()

            # เติม progress bar ระหว่างการตอบสนอง
            for i in range(10):
                time.sleep(0.1)  # จำลองเวลาในการอัพโหลด
                pbar.update(10)

        if response_data['status'] == 'success':
            print(f"\nData uploaded successfully: {response_data.get('message', 'Uploaded successfully')}")
        else:
            print(f"\nUpload failed: {response_data.get('message', 'Upload failed')}")

    except requests.RequestException as e:
        print(f"\nRequest failed: {e}")

def process_csv_file(csv_file):
    """Process CSV file and convert it to a JSON format."""
    print(f"Processing file: {csv_file}")
    try:
        with open(csv_file, 'r') as file:
            reader = csv.DictReader(file)
            data = list(reader)  # Convert CSV rows to a list of dictionaries

        # แสดง progress bar ขณะประมวลผล CSV
        with tqdm(total=len(data), desc="Processing CSV") as pbar:
            for _ in data:
                time.sleep(0.1)  # จำลองเวลาในการประมวลผล
                pbar.update(1)

        # Convert to JSON format
        print("\nConverting CSV to JSON format...")

        # เริ่มการแสดง progress bar สำหรับการแปลงเป็น JSON
        with tqdm(total=100, desc="Converting to JSON") as pbar:
            time.sleep(0.5)  # จำลองการแปลง
            pbar.update(100)

        # Upload JSON data
        upload_data(data, upload_url)

    except IOError as e:
        print(f"\nFailed to open file: {e}")

def main():
    result_file = 'result_duplicate.csv'
    process_csv_file(result_file)

if __name__ == "__main__":
    upload_url = 'https://thailandfxwarrior.com/lab/upload_csv_duplicate.php'  # เปลี่ยนเป็น endpoint ที่รองรับ JSON
    main()
