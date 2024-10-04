import os
import requests
import subprocess
import csv
import json
import time
from tqdm import tqdm

# Function to download a file with a progress bar
def download_file(url, local_filename):
    with requests.get(url, stream=True) as r:
        r.raise_for_status()
        total_size = int(r.headers.get('content-length', 0))
        chunk_size = 8192  # Chunk size of 8KB

        with tqdm(total=total_size, unit='iB', unit_scale=True, desc=local_filename) as pbar:
            with open(local_filename, 'wb') as f:
                for chunk in r.iter_content(chunk_size=chunk_size):
                    if chunk:
                        f.write(chunk)
                        pbar.update(len(chunk))

# Save selected subject to a file
def save_subject_to_file(subject):
    with open('subject.dat', 'w') as f:
        f.write(subject)

# Function to compile and run C code, then compare output
def compile_and_run(student_file, lab_input, expected_output, timeout=5):
    exe_file = student_file.replace('.c', '.exe')
    try:
        # Compile the student's C file
        compile_process = subprocess.run(['gcc', student_file, '-o', exe_file], capture_output=True)
        if compile_process.returncode != 0:
            return None, None, f"Compile error: {compile_process.stderr.decode('utf-8')}"
        
        # Run the compiled file and capture output
        with open(lab_input, 'r') as infile, open('student_output.txt', 'w') as outfile:
            run_process = subprocess.run([f'./{exe_file}'], stdin=infile, stdout=outfile, stderr=subprocess.PIPE, timeout=timeout)

        if run_process.returncode != 0:
            return None, None, f"Runtime error: {run_process.stderr.decode('utf-8')}"
    
    except subprocess.TimeoutExpired:
        return None, None, f"Timeout error ({timeout} seconds)"
    except Exception as e:
        return None, None, f"Error: {str(e)}"
    
    # Read student output
    with open('student_output.txt', 'r') as student_output_file:
        student_output = student_output_file.read().strip()

    if len(student_output) > 10000:
        return None, None, "Output exceeded the size limit (10,000 characters)"

    # Read the expected output from teacher
    with open(expected_output, 'r') as expected_output_file:
        expected_output_content = expected_output_file.read().strip()

    return student_output, expected_output_content, None

# Limit submission to 50 times a day
def check_submission_limit(student_id):
    submission_file = f'submission_{student_id}.dat'
    today = time.strftime("%Y-%m-%d")
    
    # Check if the file exists
    if os.path.exists(submission_file):
        with open(submission_file, 'r') as f:
            data = json.load(f)
        date = data.get('date')
        count = data.get('count', 0)
        
        # If the date is today and the limit has been reached
        if date == today and count >= 50:
            return False, f"Submission limit reached for today (50 submissions)."
        
        # If the date is today but limit not reached
        elif date == today:
            data['count'] += 1
        else:
            data = {'date': today, 'count': 1}
    else:
        data = {'date': today, 'count': 1}
    
    # Save the new count
    with open(submission_file, 'w') as f:
        json.dump(data, f)
    
    return True, "Submission allowed."

# Save the results to a CSV
def save_results_to_csv(results, lab_number, subject):
    result_folder = f'result/{subject}'
    if not os.path.exists(result_folder):
        os.makedirs(result_folder)
    
    csv_file = os.path.join(result_folder, f'results_{lab_number}.csv')
    with open(csv_file, mode='w', newline='') as file:
        writer = csv.writer(file)
        writer.writerow(['student id', 'lab', 'student output', 'teacher output', 'result', 'subject'])
        for result in results:
            writer.writerow(result)

# Upload data to the server
def upload_data(data, upload_url):
    headers = {'Content-Type': 'application/json'}
    try:
        response = requests.post(upload_url, data=json.dumps(data), headers=headers)
        response.raise_for_status()
        response_data = response.json()

        if response_data['status'] != 'success':
            print(f"Upload failed: {response_data.get('message', 'Upload failed')}")
    except requests.RequestException as e:
        print(f"Request failed: {e}")

# Main function
def main():
    student_id = input("Enter your student ID:")

    # Check the submission limit
    allowed, message = check_submission_limit(student_id)
    if not allowed:
        print(message)
        return
    
    subject = 'ENGCC304'

    # Save the subject to file
    save_subject_to_file(subject)

    # Download teacher input/output files
    input_folder = f'teacher_input/{subject}'
    output_folder = f'teacher_output/{subject}'
    
    if not os.path.exists(input_folder):
        os.makedirs(input_folder)
    if not os.path.exists(output_folder):
        os.makedirs(output_folder)

    input_url = f'https://thailandfxwarrior.com/lab/teacher_input/{subject}/'
    output_url = f'https://thailandfxwarrior.com/lab/teacher_output/{subject}/'

    # Download input files
    for i in range(1, 4):  # Assume 3 lab assignments
        download_file(f'{input_url}lab{i}_input.dat', f'{input_folder}/lab{i}_input.dat')
        download_file(f'{output_url}lab{i}_output.dat', f'{output_folder}/lab{i}_output.dat')

    # Compile and run student's C code
    results = []
    lab_number = input("Enter lab number to check (e.g., 1): ")
    student_file = f'student_code/{subject}/{student_id}_lab{lab_number}.c'

    if not os.path.exists(student_file):
        print(f"Student file {student_file} not found.")
        return

    lab_input = f'{input_folder}/lab{lab_number}_input.dat'
    expected_output = f'{output_folder}/lab{lab_number}_output.dat'

    student_output, expected_output_content, error = compile_and_run(student_file, lab_input, expected_output)
    
    if error:
        print(f"Error: {error}")
        results.append([student_id, lab_number, error, 'N/A', 0, subject])
    else:
        is_correct = (student_output == expected_output_content)
        results.append([student_id, lab_number, student_output, expected_output_content, int(is_correct), subject])

    # Save results and upload
    save_results_to_csv(results, lab_number, subject)
    upload_url = f'https://thailandfxwarrior.com/lab/upload_csv.php?subject={subject}'
    upload_data(results, upload_url)

    print("Lab checked and results uploaded successfully!")

if __name__ == "__main__":
    main()
