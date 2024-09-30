#include <stdio.h>
#include <math.h>

// ฟังก์ชันตรวจสอบจำนวนเฉพาะ
int isPrime(int num) {
    if (num < 2)
        return 0; // ตัวเลขที่น้อยกว่า 2 ไม่ใช่จำนวนเฉพาะ
    for (int i = 2; i <= sqrt(num); i++) {
        if (num % i == 0)
            return 0; // ถ้าหารลงตัว แสดงว่าไม่ใช่จำนวนเฉพาะ
    }
    return 1; // ถ้าไม่หารลงตัวเลย แสดงว่าเป็นจำนวนเฉพาะ
}

int main() {
    int n;

    // รับจำนวนสมาชิกในอาเรย์
    printf("Enter N : ");
    scanf("%d", &n);

    int arr[n];

    // รับค่าลงในอาเรย์
    for (int i = 0; i < n; i++) {
        printf("Enter value[%d] : ", i);
        scanf("%d", &arr[i]);
    }

    // แสดงหัวข้อ Index
    printf("Index:  ");
    for (int i = 0; i < n; i++) {
        printf("%2d ", i);
    }
    printf("\n");

    // แสดงค่าของอาเรย์ โดยแทนที่ค่าที่ไม่ใช่จำนวนเฉพาะด้วย #
    printf("Array:  ");
    for (int i = 0; i < n; i++) {
        if (isPrime(arr[i]))
            printf("%2d ", arr[i]); // ถ้าเป็นจำนวนเฉพาะให้แสดงค่า
        else
            printf("%2s ", "#"); // ถ้าไม่ใช่จำนวนเฉพาะให้แสดงเครื่องหมาย #
    }
    printf("\n");

    return 0;
}
