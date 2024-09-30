#include <stdio.h>
#include <string.h>
#include <ctype.h>

// ฟังก์ชันในการแปลงข้อความให้เป็นตัวพิมพ์เล็กทั้งหมด
void toLowerCase(char str[]) {
    for (int i = 0; str[i]; i++) {
        str[i] = tolower(str[i]);
    }
}

// ฟังก์ชันในการตรวจสอบคำหรือวลีว่าเป็นพาลินโดรมหรือไม่
int isPalindrome(char str[]) {
    int start = 0;
    int end = strlen(str) - 1;

    // ตรวจสอบตัวอักษรจากหน้ามาหลังและหลังมาหน้า
    while (start < end) {
        if (str[start] != str[end])
            return 0; // ไม่ใช่พาลินโดรม
        start++;
        end--;
    }
    return 1; // เป็นพาลินโดรม
}

int main() {
    char str[100];

    // รับค่าจากผู้ใช้
    printf("Enter word: ");
    scanf("%s", str);

    // แปลงข้อความให้เป็นตัวพิมพ์เล็กทั้งหมด
    toLowerCase(str);

    // เรียกใช้ฟังก์ชัน isPalindrome เพื่อตรวจสอบและแสดงผลลัพธ์
    if (isPalindrome(str))
        printf("Pass.\n");
    else
        printf("Not Pass.\n");

    return 0;
}
