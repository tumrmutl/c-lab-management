import csv
import mysql.connector
import os
import glob

# เชื่อมต่อกับฐานข้อมูล MySQL
conn = mysql.connector.connect(
    host="YOUR_SERVER_IP",
    user="YOUR_USERNAME",
    password="YOUR_PASSWORD",
    database="student"
)

cursor = conn.cursor()

# กำหนดโฟลเดอร์ที่เก็บไฟล์ CSV
result_folder = 'result/'

# ค้นหาไฟล์ result_labX.csv ทั้งหมดในโฟลเดอร์
csv_files = glob.glob(os.path.join(result_folder, 'result_lab*.csv'))

# อ่านแต่ละไฟล์ CSV
for csv_file in csv_files:
    print(f"Processing file: {csv_file}")
    with open(csv_file, mode='r') as file:
        csv_reader = csv.DictReader(file)
        for row in csv_reader:
            student_id = row['student id']
            lab = row['lab']
            student_output = row['student output']
            teacher_output = row['teacher output']
            result = row['result']

            # อัปเดตข้อมูลในฐานข้อมูล หากค่า result เดิมเป็น 0
            update_query = """
            UPDATE student
            SET result = %s, student_output = %s, teacher_output = %s
            WHERE student_id = %s AND lab = %s AND result = 0
            """
            cursor.execute(update_query, (result, student_output, teacher_output, student_id, lab))
            conn.commit()

# ปิดการเชื่อมต่อฐานข้อมูล
cursor.close()
conn.close()

print("All files processed and uploaded.")
