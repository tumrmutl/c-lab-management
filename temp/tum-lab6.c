#include <stdio.h>

int main() {
    int N;

    // รับค่า N จากผู้ใช้
    printf("Enter value: ");
    scanf("%d", &N);

    printf("Series: ");

    // ตรวจสอบว่า N เป็นเลขคี่หรือเลขคู่
    if (N % 2 == 1) {
        // หาก N เป็นเลขคี่ ให้แสดงตัวเลขคี่จาก 1 ถึง N
        for (int i = 1; i <= N; i += 2) {
            printf("%d ", i);
        }
    } else {
        // หาก N เป็นเลขคู่ ให้แสดงตัวเลขคู่จาก N ถึง 0
        for (int i = N; i >= 0; i -= 2) {
            printf("%d ", i);
        }
    }

    printf("\n");

    return 0;
}
