import subprocess
import time
from pynput import keyboard
from rich.console import Console
from rich.progress import Progress
from rich.text import Text
from rich.panel import Panel
from datetime import datetime, timedelta

delay = 60 * 10  # 10 นาที
console = Console()

# ฟังก์ชันสำหรับรันไฟล์ Python
def run_python_file(file_name):
    try:
        subprocess.run(["python", file_name], check=True)
    except subprocess.CalledProcessError as e:
        console.print(f"เกิดข้อผิดพลาดขณะรัน {file_name}: {e}", style="bold red")
        exit(1)

# ฟังก์ชันแสดงสถิติ
def show_stats():
    global run_count
    start_time = datetime.now()
    
    # อัปเดตสถิติ
    run_count += 1
    console.clear()
    console.print(Panel(Text("โปรแกรมกำลังรัน...", style="bold green"), title="สถานะ"))
    console.print(f"รันโปรแกรมไปแล้ว: {run_count} ครั้ง")
    console.print(f"รันครั้งล่าสุดเมื่อ: {start_time.strftime('%Y-%m-%d %H:%M:%S')}")
    console.print("\n\n")

# ฟังก์ชันแสดง Countdown
def countdown():
    with Progress() as progress:
        task = progress.add_task("[cyan]เวลาที่เหลือ...", total=delay)
        for remaining in range(delay, 0, -1):
            if stop_program:
                break
            progress.update(task, advance=1)
            time.sleep(1)

# ตัวแปรเพื่อจัดการการรัน
stop_program = False
run_count = 0

# ฟังก์ชันหลักสำหรับรันไฟล์เป็นลูป
def main_loop():
    global stop_program
    stop_program = False

    def on_press(key):
        global stop_program
        if key == keyboard.Key.esc:
            console.print("ตรวจพบการกด ESC, รัน loop สุดท้ายและหยุดการทำงาน...")
            stop_program = True
            return False  # หยุด listener เมื่อกด ESC

    listener = keyboard.Listener(on_press=on_press)
    listener.start()
    
    while not stop_program:
        # รันไฟล์ที่ต้องการ
        run_python_file("0_download_student_code_to_my_folder.py")
        run_python_file("1_teacher_check_lab.py")
        run_python_file("2_teacher_upload_score.py")
        
        console.print("รันไฟล์ทั้งหมดเสร็จสิ้น")

        # แสดงสถิติ
        show_stats()
        
        # เริ่ม Countdown
        countdown()

    listener.stop()  # หยุด listener

# เริ่มการทำงาน
if __name__ == "__main__":
    main_loop()
    console.print("โปรแกรมหยุดทำงานแล้ว")
