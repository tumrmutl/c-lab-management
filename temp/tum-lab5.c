#include <stdio.h>
#include <ctype.h>

int main() {
    float score;
    char input[100];

    // รับค่าจากผู้ใช้
    printf("Enter score: ");
    scanf("%s", input);

    // ตรวจสอบว่าผู้ใช้กรอกตัวเลขหรือไม่
    int is_number = 1;
    for (int i = 0; input[i] != '\0'; i++) {
        if (!isdigit(input[i]) && input[i] != '.') {
            is_number = 0;
            break;
        }
    }

    // ถ้าผู้ใช้กรอกตัวอักษรที่ไม่ใช่ตัวเลข
    if (!is_number) {
        printf("Please enter number only.\n");
    } else {
        // แปลง input ให้เป็นตัวเลข
        sscanf(input, "%f", &score);

        // ตรวจสอบเกรดด้วย if-else
        if (score < 50) {
            printf("F !\n");
        } else if (score >= 50 && score < 55) {
            printf("D !\n");
        } else if (score >= 55 && score < 60) {
            printf("D+ !\n");
        } else if (score >= 60 && score < 65) {
            printf("C !\n");
        } else if (score >= 65 && score < 70) {
            printf("C+ !\n");
        } else if (score >= 70 && score < 75) {
            printf("B !\n");
        } else if (score >= 75 && score < 80) {
            printf("B+ !\n");
        } else if (score >= 80) {
            printf("A !\n");
        }
    }

    return 0;
}
