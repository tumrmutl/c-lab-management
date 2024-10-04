import os
import subprocess
import csv
from tqdm import tqdm

def compile_and_run(student_file, lab_input, expected_output, timeout=5):
    exe_file = student_file.replace('.c', '.exe')
    try:
        compile_process = subprocess.run(['gcc', student_file, '-o', exe_file], capture_output=True)
        if compile_process.returncode != 0:
            return None, None, f"ข้อผิดพลาดในการคอมไพล์: {compile_process.stderr.decode('utf-8')}"
        
        with open(lab_input, 'r') as infile, open('student_output.txt', 'w') as outfile:
            run_process = subprocess.run([f'./{exe_file}'], stdin=infile, stdout=outfile, stderr=subprocess.PIPE, timeout=timeout)
        
        if run_process.returncode != 0:
            return None, None, f"ข้อผิดพลาดในการรันโปรแกรม: {run_process.stderr.decode('utf-8')}"
    
    except subprocess.TimeoutExpired:
        return None, None, f"โปรแกรมรันเกินเวลาที่กำหนด ({timeout} วินาที)"
    except Exception as e:
        return None, None, f"ข้อผิดพลาด: {str(e)}"
    
    with open('student_output.txt', 'r') as student_output_file:
        student_output = student_output_file.read().strip()

    if len(student_output) > 10000:
        return None, None, "output เกินขนาดที่กำหนด (10,000 ตัวอักษร)"

    with open(expected_output, 'r') as expected_output_file:
        expected_output_content = expected_output_file.read().strip()

    student_output_filtered = student_output.replace("enter line :", "").strip()

    return student_output_filtered, expected_output_content, None

def save_results_to_csv(results, lab_number, subject):
    result_folder = f'result/{subject}'
    if not os.path.exists(result_folder):
        os.makedirs(result_folder)
    
    csv_file = os.path.join(result_folder, f'results_{lab_number}.csv')
    # เพิ่มการกำหนด encoding เป็น 'utf-8'
    with open(csv_file, mode='w', newline='', encoding='utf-8') as file:
        writer = csv.writer(file)
        writer.writerow(['student id', 'lab', 'student output', 'teacher output', 'result', 'subject'])
        for result in results:
            writer.writerow(result)


def main():
    # อ่านข้อมูลรหัสวิชาจาก subject.dat
    with open('subject.dat', 'r') as subject_file:
        subject = subject_file.read().strip()

    # อัปเดตโฟลเดอร์ตามวิชา
    student_code_folder = f'student_code/{subject}'
    input_folder = f'teacher_input/{subject}'
    output_folder = f'teacher_output/{subject}'

    if not os.path.exists(f'result/{subject}'):
        os.makedirs(f'result/{subject}')

    # ดึงเลข lab จากชื่อไฟล์ใน input_folder
    lab_numbers = set()
    for filename in os.listdir(input_folder):
        if filename.endswith('_input.dat'):
            lab_number = filename.split('_')[0]
            lab_numbers.add(lab_number)

    for lab_number in lab_numbers:
        lab_input = os.path.join(input_folder, f'{lab_number}_input.dat')
        expected_output = os.path.join(output_folder, f'{lab_number}_output.dat')
        
        results = []
        student_files = [f for f in os.listdir(student_code_folder) if f.endswith('.c')]

        # ใช้ tqdm ในการแสดง Progress Bar
        with tqdm(total=len(student_files), desc=f'Processing Lab {lab_number}', unit='file') as pbar:
            for student_file in student_files:
                parts = student_file.split('_')
                if len(parts) == 2:
                    student_id = parts[0]
                    student_lab_number = parts[1].replace('.c', '')

                    if student_lab_number == lab_number:
                        student_file_path = os.path.join(student_code_folder, student_file)
                        print(f"ตรวจสอบไฟล์โค้ด: {student_file_path}")

                        student_output, expected_output_content, error = compile_and_run(student_file_path, lab_input, expected_output)

                        if error:
                            print(f"{student_file}: เกิดข้อผิดพลาดขณะรันโปรแกรม: {error}")
                            results.append([student_id, lab_number, error, 'N/A', 0, subject])  # เพิ่มข้อผิดพลาดลงใน CSV
                        else:
                            is_correct = (student_output == expected_output_content)
                            results.append([student_id, lab_number, student_output, expected_output_content, int(is_correct), subject])

                        pbar.update(1)

        if results:
            save_results_to_csv(results, lab_number, subject)
            print(f"บันทึกผลลัพธ์ลงไฟล์ CSV เรียบร้อย: {subject}/results_{lab_number}.csv")
        else:
            print(f"ไม่มีผลลัพธ์สำหรับ lab {lab_number}")
        
        results.clear()

if __name__ == "__main__":
    main()
