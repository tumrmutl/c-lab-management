#include <stdio.h>

// ฟังก์ชันสำหรับสลับค่าของตัวแปรสองตัว
void swapNumbers(int *ptr1, int *ptr2) {
    *ptr1 = *ptr1 + *ptr2;
    *ptr2 = *ptr1 - *ptr2;
    *ptr1 = *ptr1 - *ptr2;
}

int main() {
    int num1, num2;
    int *ptr1, *ptr2;

    // รับค่าจากผู้ใช้
    printf("Enter num1: ");
    scanf("%d", &num1);

    printf("Enter num2: ");
    scanf("%d", &num2);

    // กำหนด pointer ให้ชี้ไปยังตัวแปร num1 และ num2
    ptr1 = &num1;
    ptr2 = &num2;

    // แสดงค่าก่อนการสลับ
    printf("Before swap (num1 & num2): %d, %d\n", num1, num2);

    // เรียกใช้ฟังก์ชัน swapNumbers
    swapNumbers(ptr1, ptr2);

    // แสดงค่าหลังการสลับ
    printf("After swap (num1 & num2): %d, %d\n", num1, num2);

    return 0;
}
