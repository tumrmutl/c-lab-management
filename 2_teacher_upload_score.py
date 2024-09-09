import os
import glob
import requests
import csv
import json
import pyperclip  # สำหรับการคัดลอกข้อมูลไปยัง clipboard

result_folder = 'result/'

def list_files_in_folder(folder):
    """Print all files in the specified folder."""
    try:
        files = os.listdir(folder)
        print(f"Files in folder '{folder}':")
        if not files:
            print("No files found.")
        else:
            for file in files:
                print(f" - {file}")
    except Exception as e:
        print(f"Error listing files in folder '{folder}': {e}")

def copy_to_clipboard(data):
    """Copy data to clipboard."""
    try:
        pyperclip.copy(data)
        print("Data copied to clipboard successfully.")
    except Exception as e:
        print(f"Failed to copy data to clipboard: {e}")

def upload_data(data, upload_url):
    """Send JSON data to the server."""
    try:
        headers = {'Content-Type': 'application/json'}
        response = requests.post(upload_url, data=json.dumps(data), headers=headers)
        response.raise_for_status()  # ตรวจสอบว่า request สำเร็จ
        response_data = response.json()

        if response_data['status'] == 'success':
            print(f"Data uploaded successfully: {response_data.get('message', 'Uploaded successfully')}")
        else:
            print(f"Upload failed: {response_data.get('message', 'Upload failed')}")
    except requests.RequestException as e:
        print(f"Request failed: {e}")

def process_csv_file(csv_file):
    """Process CSV file and convert it to a JSON format."""
    print(f"Processing file: {csv_file}")
    try:
        with open(csv_file, 'r') as file:
            reader = csv.DictReader(file)
            data = list(reader)  # Convert CSV rows to a list of dictionaries
            
        # Convert to JSON format
        json_data = json.dumps(data, indent=4)
        print(f"Converted CSV to JSON: \n{json_data}")

        # Copy data to clipboard
        copy_to_clipboard(json_data)
        
        # Upload JSON data
        upload_data(data, upload_url)

    except IOError as e:
        print(f"Failed to open file: {e}")

def main():
    # Print all files in the result folder
    list_files_in_folder(result_folder)
    
    # Correct the pattern to match the actual file names
    pattern = os.path.join(result_folder, 'results_lab*.csv')
    csv_files = glob.glob(pattern)
    if not csv_files:
        print(f"No CSV files found with pattern '{pattern}'.")

    for csv_file in csv_files:
        process_csv_file(csv_file)

if __name__ == "__main__":
    upload_url = 'https://thailandfxwarrior.com/lab/upload_csv.php'  # เปลี่ยนเป็น endpoint ที่รองรับ JSON
    main()
