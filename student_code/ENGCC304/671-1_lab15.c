#include <stdio.h>
#include <ctype.h>

int countWordsInFile(const char *filename) {
    FILE *file;
    char ch;
    int wordCount = 0;
    int inWord = 0;

    file = fopen(filename, "r");  // เปิดไฟล์ในโหมดอ่าน
    if (file == NULL) {
        printf("Could not open file %s\n", filename);
        return -1;  // คืนค่า -1 เมื่อไฟล์เปิดไม่ได้
    }

    // อ่านไฟล์ทีละตัวอักษร
    while ((ch = fgetc(file)) != EOF) {
        // ตรวจสอบว่าตัวอักษรที่อ่านไม่ใช่เครื่องหมายเว้นวรรคหรือสัญลักษณ์
        if (isalnum(ch)) {
            if (!inWord) {
                wordCount++;  // นับคำใหม่เมื่อเจอคำแรกของกลุ่มตัวอักษร
                inWord = 1;
            }
        } else {
            inWord = 0;  // สิ้นสุดคำเมื่อเจอตัวอักษรที่ไม่ใช่ตัวอักษรหรือตัวเลข
        }
    }

    fclose(file);  // ปิดไฟล์
    return wordCount;
}

int main() {
    char filename[100];
    int totalWords;

    // รับชื่อไฟล์จากผู้ใช้
    printf("Enter file name: ");
    scanf("%s", filename);

    // เรียกใช้ฟังก์ชันเพื่ออ่านจำนวนคำจากไฟล์
    totalWords = countWordsInFile(filename);

    if (totalWords != -1) {
        printf("Total number of words in '%s': %d words\n", filename, totalWords);
    }

    return 0;
}
