import os
import glob
import requests
import csv

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

def upload_file(csv_file):
    """Upload a single file and return the result."""
    print(f"Attempting to upload file: {csv_file}")
    try:
        # Open the CSV file and skip the header
        with open(csv_file, 'r') as file:
            reader = csv.reader(file)
            header = next(reader)  # Skip the header row
            # Convert remaining rows to a list of dictionaries for upload
            data = [row for row in reader]

        # Create a temporary file with the data (excluding the header)
        temp_file_path = csv_file + '.temp'
        with open(temp_file_path, 'w', newline='') as temp_file:
            writer = csv.writer(temp_file)
            writer.writerows(data)

        # Upload the temporary file
        with open(temp_file_path, 'rb') as file:
            files = {'csv_file': file}
            response = requests.post(upload_url, files=files)
            response.raise_for_status()  # Check HTTP status code

            response_data = response.json()
            if response_data['status'] == 'success':
                return (csv_file, True, response_data.get('message', 'Uploaded successfully'))
            else:
                return (csv_file, False, response_data.get('message', 'Upload failed'))
    except requests.RequestException as e:
        return (csv_file, False, f"Request failed: {e}")
    except IOError as e:
        return (csv_file, False, f"Failed to open file: {e}")
    finally:
        # Clean up the temporary file
        if os.path.exists(temp_file_path):
            os.remove(temp_file_path)

def main():
    # Print all files in the result folder
    list_files_in_folder(result_folder)
    
    # Correct the pattern to match the actual file names
    pattern = os.path.join(result_folder, 'results_lab*.csv')
    csv_files = glob.glob(pattern)
    if not csv_files:
        print(f"No CSV files found with pattern '{pattern}'.")

    results = []
    for csv_file in csv_files:
        print(f"\nProcessing file: {csv_file}")
        file_name, success, message = upload_file(csv_file)
        results.append((file_name, success, message))

    print("\nSummary of all files:")
    for file_name, success, message in results:
        if success:
            print(f"{file_name} uploaded successfully: {message}")
        else:
            print(f"{file_name} failed to upload: {message}")

if __name__ == "__main__":
    upload_url = 'https://thailandfxwarrior.com/lab/upload_csv.php'
    main()
