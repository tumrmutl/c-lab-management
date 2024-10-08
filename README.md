# c-lab-management

## การใช้งาน

### นักศึกษา
1. เปลี่ยนรหัสใน server ในไฟล์ **pass.dat** เพื่อเอารหัสส่ง Lab ให้นักศึกษาเป็นวันๆ ไป เฉพาะนักศึกษาที่เข้าเรียนวันนั้น และรู้รหัสเท่านั้น ถึงจะสามารถส่ง Lab ได้ โดยเปลี่ยนรหัสได้[ที่นี่](https://thailandfxwarrior.com/lab/set_password.php) และรับรหัสไปให้นักศึกษาได้[ที่นี่](https://thailandfxwarrior.com/lab/get_password.php)
2. ให้อัพฯ โค้ดโปรแกรม lab ของตัวเอง เพื่อทำการส่ง Lab [ที่นี่](https://thailandfxwarrior.com/lab/student_lab.php)
3. นักศึกษาสามารถดูข้อมูลการส่ง Lab ทั้งหมดของตัวเองได้ [ที่นี่](#)

### ผู้ตรวจโค้ดโปรแกรม
1. รันไฟล์ **0_download_student_code_to_my_folder.py** เพื่อโหลดโค้ดของนักศึกษาจากเว็บ มาไว้ใน Folder ส่วนตัวของเรา
2. ตรวจ Lab
    - ต้องมีโค้ด input แต่ละ Lab เอาไว้ใน ./teacher_input/
    - ต้องมีโค้ด output แต่ละ Lab เอาไว้ใน ./teacher_output/
    - เมื่อมีทุกอย่างครบ ก็รันไฟล์ **1_teacher_check_lab.py** เพื่อตรวจ Lab โดยรันทีเดียว โปรแกรมจะตรวจทุก Lab หมดเลย
3. อัพโหลดคะแนนการตรวจขึ้นเว็บ โดยรันไฟล์ **2_teacher_upload_score** 

## สิ่งที่ต้องปรับปรุงแก้ไข
- ตอนนี้สอนหลายวิชา แต่ว่าไฟล์ Lab มันสามารถตรวจได้แค่วิชา ENGCC304 เท่านั้น ดังนั้นโปรแกรมควรจะแยกได้ว่า ไฟล์ Lab นี้เป็นของวิชาไหน ~~(อาจกำหนดตอนตั้งชื่อไฟล์เลย)~~ ถ้าเอาง่ายสุด ตอนตรวจ ต้องถามให้เลือกวิชา > โปรแกรมไปโหลดที่โฟลเดอร์วิชานั้นๆ > ตรวจงานที่โฟลเดอร์นั้นๆ > ส่งผลลัพธ์ไปที่โฟลเดอร์นั้นๆ > บันทึกรหัสวิชาเข้าไปด้วย .
- ~~ต้องทำส่วนให้นักศึกษาเข้ามาดูคะแนนของตัวเองได้ โดยการกรอกรหัสนักศึกษาของตนเอง~~ > เลือกวิชา
- ~~ตอนนี้ยังตรวจโค้ดโปรแกรมของนักศึกษาไม่ได้ ไม่รู้ว่าลอกเพื่อนคนไหนมาบ้าง ดังนั้นควรมี Database แยกไปเลย ว่าใครลอกโค้ดใครบ้าง~~
- ~~Database ของนักศึกษา ควรมีข้อมูลนักศึกษาให้ครบกว่านี้ เช่น ชื่อนามสกุล, ชื่อเล่น, อีเมล, FB, IG, Line ID, Tel~~
- ควรมีระบบลบไฟล์ของนักศึกษา และ ไฟล์ csv ที่อัพโหลดเข้าไปในระบบด้วย

## ตัวอย่างไฟล์ผลลัพธ์แสดงการคัดลอกโค้ดโปรแกรมของนักศึกษา
### ผลลัพธ์ในไฟล์ csv
Lab ID,File 1,File 2,Similarity (%),Hash Similarity (%),Structural Similarity (%),AST Similarity (%),Token Similarity (%),Embedding Similarity (%),TF-IDF Similarity (%),Graph Similarity (%)
lab1,673333333-3,673-3,100.00,100.00,100.00,0.00,100.00,100.00,100.00,0.00
lab1,673333333-3,671-1,100.00,100.00,100.00,0.00,100.00,100.00,100.00,0.00
lab1,673-3,673333333-3,100.00,100.00,100.00,0.00,100.00,100.00,100.00,0.00
lab1,673-3,671-1,100.00,100.00,100.00,0.00,100.00,100.00,100.00,0.00
lab1,671-1,673333333-3,100.00,100.00,100.00,0.00,100.00,100.00,100.00,0.00
lab1,671-1,673-3,100.00,100.00,100.00,0.00,100.00,100.00,100.00,0.00
lab3,22-2,11-1,46.23,0.00,55.91,0.00,76.83,98.43,67.97,0.00
lab3,22-2,9999999-9,41.48,0.00,42.31,0.00,54.80,97.16,42.55,0.00
lab3,11-1,22-2,46.23,0.00,55.91,0.00,76.83,98.43,67.97,0.00
lab3,11-1,9999999-9,44.60,0.00,34.92,0.00,64.72,96.48,47.94,0.00
lab3,9999999-9,22-2,41.48,0.00,42.31,0.00,54.80,97.16,42.55,0.00
lab3,9999999-9,11-1,44.60,0.00,34.92,0.00,64.72,96.48,47.94,0.00
lab2,66543222010-0,672-2,31.00,0.00,22.68,0.00,73.99,97.06,55.23,0.00
lab2,672-2,66543222010-0,31.00,0.00,22.68,0.00,73.99,97.06,55.23,0.00

### คำอธิบาย
ในการตัดสินใจเลือกตัวเลขที่เชื่อถือได้มากที่สุดในผลลัพธ์จากไฟล์ CSV ของคุณ มีหลายปัจจัยที่ต้องพิจารณา:

**Hash Similarity**: ตัวเลขนี้แสดงถึงความคล้ายคลึงของไฟล์โดยการเปรียบเทียบค่าแฮช MD5 ของไฟล์ การมีค่า 100% หมายความว่าไฟล์ทั้งสองเป็นไฟล์เดียวกันและไม่มีการเปลี่ยนแปลงใดๆ การเปรียบเทียบนี้เป็นวิธีที่แม่นยำในการตรวจสอบว่ามีการคัดลอกไฟล์อย่างแน่นอน แต่จะไม่ช่วยในกรณีที่ไฟล์ถูกแก้ไขเล็กน้อยหรือมีการเปลี่ยนแปลง

**Structural Similarity**: การเปรียบเทียบนี้มุ่งเน้นที่โครงสร้างของโค้ดหลังจากการทำความสะอาด เช่น การลบคอมเมนต์และเว้นวรรค ซึ่งสามารถให้ข้อมูลเกี่ยวกับความคล้ายคลึงกันของโค้ดที่สำคัญที่สุด แต่การเปรียบเทียบนี้อาจจะไม่เหมาะสมในกรณีที่โค้ดมีการเปลี่ยนแปลงในโครงสร้างอย่างมาก

**AST Similarity**: การเปรียบเทียบนี้ใช้ต้นไม้การสังเคราะห์เชิงนามธรรม (AST) ของโค้ด ซึ่งช่วยให้เข้าใจถึงโครงสร้างของโค้ดและตรรกะที่ใช้ การเปรียบเทียบนี้จะให้ภาพรวมที่ดีเกี่ยวกับความคล้ายคลึงกันของตรรกะในโค้ด แต่ถ้าโค้ดมีการเปลี่ยนแปลงโครงสร้างมาก AST อาจจะเปลี่ยนแปลงได้

**Token Similarity**: การเปรียบเทียบนี้พิจารณาเพียงแค่ tokens ที่สำคัญในโค้ด เช่น ชื่อตัวแปรและค่าตัวเลข ซึ่งเป็นวิธีที่ดีในการตรวจจับความคล้ายคลึงกันในระดับต่ำกว่า แต่ยังคงมีข้อจำกัดในกรณีที่มีการเปลี่ยนแปลงที่สำคัญ

**Embedding Similarity**: การเปรียบเทียบนี้ใช้ embeddings จาก CodeBERT เพื่อเปรียบเทียบความคล้ายคลึงกันในเชิงลึก ซึ่งเป็นเทคนิคที่ล้ำสมัยและมีความสามารถในการจับความหมายของโค้ด แต่มีความซับซ้อนและใช้เวลานานในการคำนวณ

**TF-IDF Similarity**: การเปรียบเทียบนี้ใช้เทคนิคการเปรียบเทียบคำที่สำคัญในเอกสาร ซึ่งสามารถให้ข้อมูลที่ดีเกี่ยวกับความคล้ายคลึงกันในเนื้อหาของโค้ด

**Graph Similarity**: การเปรียบเทียบนี้มองที่กราฟของโค้ดและเปรียบเทียบความคล้ายคลึงกันของโครงสร้างกราฟ ซึ่งเป็นวิธีที่เป็นเอกลักษณ์และมีประโยชน์ในบางกรณี แต่ความเหมือนอาจจะไม่สูงหากโค้ดมีการเปลี่ยนแปลงใหญ่

**คำแนะนำ**
สำหรับ คู่ไฟล์ที่มีค่า Hash Similarity เป็น 100% (เช่น 673333333-3 กับ 673-3), คุณสามารถเชื่อถือได้ว่าพวกมันเหมือนกันอย่างแน่นอน เนื่องจากไฟล์นี้ไม่ได้มีการเปลี่ยนแปลงใดๆ

สำหรับ คู่ไฟล์ที่มีค่า Structural Similarity หรือ AST Similarity ต่ำ แต่มีค่าอื่นๆ สูง (เช่น lab3), แสดงว่าโค้ดอาจจะถูกเปลี่ยนแปลงในบางส่วนที่สำคัญ เช่น โครงสร้างหรือการใช้งานฟังก์ชันต่างๆ

Embedding Similarity และ TF-IDF Similarity ให้มุมมองที่ลึกซึ้งเกี่ยวกับเนื้อหาของโค้ด ซึ่งอาจจะช่วยในกรณีที่การเปลี่ยนแปลงโค้ดไม่ได้ส่งผลต่อความคล้ายคลึงกันมากนัก

โดยรวมแล้ว Hash Similarity เป็นตัวชี้วัดที่เชื่อถือได้มากที่สุดสำหรับความคล้ายคลึงกันที่แท้จริงในกรณีที่ไฟล์ไม่ถูกเปลี่ยนแปลงเลย แต่ถ้าต้องการข้อมูลที่ครอบคลุมมากขึ้น คุณควรพิจารณาตัวเลขจาก Token Similarity, Embedding Similarity, และ TF-IDF Similarity โดยเฉพาะในกรณีที่ไฟล์มีการเปลี่ยนแปลงในเชิงโครงสร้างหรือเนื้อหา.
