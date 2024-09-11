import subprocess

# ฟังก์ชันสำหรับรันไฟล์ Python
def run_python_file(file_name):
    try:
        # เรียกใช้ไฟล์ Python
        subprocess.run(["python", file_name], check=True)
    except subprocess.CalledProcessError as e:
        print(f"เกิดข้อผิดพลาดขณะรัน {file_name}: {e}")
        exit(1)

# เริ่มการรันไฟล์ทีละไฟล์ตามลำดับ
if __name__ == "__main__":
    # รันไฟล์แรกที่ต้องการให้ผู้ใช้กรอกข้อมูล
    run_python_file("0_download_student_code_to_my_folder.py")
    
    # รันไฟล์ถัดๆไปหลังจากที่ไฟล์แรกทำงานเสร็จ
    run_python_file("1_teacher_check_lab.py")
    run_python_file("2_teacher_upload_score.py")
    run_python_file("3_check_duplicate_code.py")
    run_python_file("4_teacher_upload_duplicate_score.py")

    print("รันไฟล์ทั้งหมดเสร็จสิ้น")
