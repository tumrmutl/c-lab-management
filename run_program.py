import subprocess
import time
from pynput import keyboard

delay = 60 * 30

# ฟังก์ชันสำหรับรันไฟล์ Python
def run_python_file(file_name):
    try:
        # เรียกใช้ไฟล์ Python
        subprocess.run(["python", file_name], check=True)
    except subprocess.CalledProcessError as e:
        print(f"เกิดข้อผิดพลาดขณะรัน {file_name}: {e}")
        exit(1)

# ฟังก์ชันหลักสำหรับรันไฟล์เป็นลูป
def main_loop():
    stop_program = False  # ตัวแปรสำหรับหยุดโปรแกรม
    
    def on_press(key):
        nonlocal stop_program
        try:
            if key == keyboard.Key.esc:
                print("ตรวจพบการกด ESC, รัน loop สุดท้ายและหยุดการทำงาน...")
                stop_program = True
                return False  # หยุด listener เมื่อกด ESC
        except AttributeError:
            pass

    while not stop_program:
        # รันไฟล์แรกที่ต้องการให้ผู้ใช้กรอกข้อมูล
        run_python_file("0_download_student_code_to_my_folder.py")
        
        # รันไฟล์ถัดๆไปหลังจากที่ไฟล์แรกทำงานเสร็จ
        run_python_file("1_teacher_check_lab.py")
        run_python_file("2_teacher_upload_score.py")
        # run_python_file("3_check_duplicate_code.py")
        # run_python_file("4_teacher_upload_duplicate_score.py")
        
        print("รันไฟล์ทั้งหมดเสร็จสิ้น")

        # รอการกดปุ่ม ESC ในช่วงเวลา 5 นาที
        listener = keyboard.Listener(on_press=on_press)
        listener.start()

        # รอ 5 นาที (300 วินาที) หรือจนกว่าจะตรวจพบการกด ESC
        for _ in range( delay ):
            if stop_program:
                break
            time.sleep(1)
        
        listener.stop()  # หยุด listener

# เริ่มการทำงาน
if __name__ == "__main__":
    main_loop()
    print("โปรแกรมหยุดทำงานแล้ว")
