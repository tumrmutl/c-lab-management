# c-lab-management

## การใช้งาน

### นักศึกษา
1. เปลี่ยนรหัสใน server ในไฟล์ **pass.dat** เพื่อเอารหัสส่ง Lab ให้นักศึกษาเป็นวันๆ ไป เฉพาะนักศึกษาที่เข้าเรียนวันนั้น และรู้รหัสเท่านั้น ถึงจะสามารถส่ง Lab ได้
2. ให้อัพฯ โค้ดโปรแกรม lab ของตัวเอง เพื่อทำการส่ง Lab ที่ [https://thailandfxwarrior.com/lab/student_lab.php](https://thailandfxwarrior.com/lab/student_lab.php)
3. นักศึกษาสามารถดูข้อมูลการส่ง Lab ทั้งหมดของตัวเองได้ [ที่นี่](#)

### ผู้ตรวจโค้ดโปรแกรม
1. รันไฟล์ **0_download_student_code_to_my_folder.py** เพื่อโหลดโค้ดของนักศึกษาจากเว็บ มาไว้ใน Folder ส่วนตัวของเรา
2. ตรวจ Lab
    - ต้องมีโค้ด input แต่ละ Lab เอาไว้ใน ./teacher_input/
    - ต้องมีโค้ด output แต่ละ Lab เอาไว้ใน ./teacher_output/
    - เมื่อมีทุกอย่างครบ ก็รันไฟล์ **1_teacher_check_lab.py** เพื่อตรวจ Lab โดยรันทีเดียว โปรแกรมจะตรวจทุก Lab หมดเลย
3. อัพโหลดคะแนนการตรวจขึ้นเว็บ โดยรันไฟล์ **2_teacher_upload_score** 