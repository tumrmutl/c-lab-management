import os
import subprocess
import csv

def compile_and_run(student_file, lab_input, expected_output):
    # คอมไพล์โค้ดของนักศึกษา
    exe_file = student_file.replace('.c', '.exe')
    compile_process = subprocess.run(['gcc', student_file, '-o', exe_file], capture_output=True)
    
    if compile_process.returncode != 0:
        return None, None, compile_process.stderr.decode('utf-8')
    
    # รันโปรแกรมพร้อมกับ input
    with open(lab_input, 'r') as infile, open('student_output.txt', 'w') as outfile:
        run_process = subprocess.run([f'./{exe_file}'], stdin=infile, stdout=outfile, stderr=subprocess.PIPE)
    
    if run_process.returncode != 0:
        return None, None, run_process.stderr.decode('utf-8')

    # อ่าน output ของนักศึกษา
    with open('student_output.txt', 'r') as student_output_file:
        student_output = student_output_file.read().strip()

    # อ่าน expected output ของอาจารย์
    with open(expected_output, 'r') as expected_output_file:
        expected_output_content = expected_output_file.read().strip()

    return student_output, expected_output_content, None

def save_results_to_csv(results, lab_number):
    csv_file = f'result/results_{lab_number}.csv'
    with open(csv_file, mode='w', newline='') as file:
        writer = csv.writer(file)
        writer.writerow(['student id', 'lab', 'student output', 'teacher output', 'result'])
        for result in results:
            writer.writerow(result)

def main():
    lab_number = input("กรุณาใส่ชื่อแล็บ (เช่น 'lab1'): ")

    student_code_folder = 'student_code'
    lab_input = f'teacher_input/{lab_number}_input.dat'
    expected_output = f'teacher_output/{lab_number}_output.dat'

    results = []

    # ตรวจสอบโฟลเดอร์ที่เก็บโค้ดของนักศึกษา
    for student_file in os.listdir(student_code_folder):
        if student_file.endswith('.c'):
            student_file_path = os.path.join(student_code_folder, student_file)
            print(f"ตรวจสอบไฟล์โค้ด: {student_file_path}")

            student_output, expected_output_content, error = compile_and_run(student_file_path, lab_input, expected_output)

            if error:
                print(f"{student_file}: เกิดข้อผิดพลาดขณะรันโปรแกรม: {error}")
                results.append([student_file, lab_number, 'เกิดข้อผิดพลาด', 'N/A', 0])
            else:
                is_correct = (student_output == expected_output_content)
                # print(f"ผลลัพธ์ของนักศึกษา:\n{student_output}")
                # print(f"ผลลัพธ์ที่คาดหวัง:\n{expected_output_content}")
                # print(f"การตรวจสอบ: {'ถูกต้อง' if is_correct else 'ผิดพลาด'}")

                results.append([student_file, lab_number, student_output, expected_output_content, int(is_correct)])

    # บันทึกผลลัพธ์ลง CSV
    save_results_to_csv(results, lab_number)
    print(f"บันทึกผลลัพธ์ลงไฟล์ CSV เรียบร้อย: results_{lab_number}.csv")

if __name__ == "__main__":
    main()
