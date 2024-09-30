#include <stdio.h>

int main() {
    char character;
    int num1, num2;
    float fnum;
    char str[100];

    // รับค่าตัวอักษร 1 ตัว
    printf("Enter a character: \n");
    scanf(" %c", &character);

    // รับจำนวนเต็ม 2 จำนวน
    printf("Enter two integers: \n");
    scanf("%d %d", &num1, &num2);

    // รับเลขทศนิยม 1 ตัว
    printf("Enter a floating-point number: \n");
    scanf("%f", &fnum);

    // รับข้อความ 1 ข้อความ
    printf("Enter a string: \n");
    scanf("%s", str);

    // แสดงผลข้อมูลที่รับมา
    printf("You entered character: %c\n", character);
    printf("You entered integers: %d and %d\n", num1, num2);
    printf("You entered floating-point number: %.2f\n", fnum);
    printf("You entered string: %s\n", str);

    return 0;
}
