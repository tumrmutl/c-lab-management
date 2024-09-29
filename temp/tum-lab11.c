#include <stdio.h>
#include <math.h>

// ฟังก์ชันในการตรวจสอบตัวเลขอาร์มสตรอง
int isArmstrong(int num) {
    int originalNum, remainder, result = 0, n = 0;

    // สำเนาค่าเดิมของ num เพื่อใช้ในการเปรียบเทียบในภายหลัง
    originalNum = num;

    // หาจำนวนหลักของตัวเลข
    while (originalNum != 0) {
        originalNum /= 10;
        ++n;
    }

    // รีเซ็ตค่า originalNum ให้เป็นค่าเดิม
    originalNum = num;

    // คำนวณผลรวมของเลขยกกำลังของจำนวนหลัก
    while (originalNum != 0) {
        remainder = originalNum % 10;
        result += pow(remainder, n);
        originalNum /= 10;
    }

    // ตรวจสอบว่าผลรวมที่ได้เท่ากับตัวเลขดั้งเดิมหรือไม่
    if (result == num)
        return 1; // เป็นตัวเลขอาร์มสตรอง
    else
        return 0; // ไม่เป็นตัวเลขอาร์มสตรอง
}

int main() {
    int num;

    // รับค่าจากผู้ใช้
    printf("Enter Number: ");
    scanf("%d", &num);

    // เรียกใช้ฟังก์ชัน isArmstrong เพื่อตรวจสอบและแสดงผลลัพธ์
    if (isArmstrong(num))
        printf("Pass.\n");
    else
        printf("Not Pass.\n");

    return 0;
}
