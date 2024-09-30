#include <stdio.h>

int main() {
    int arr[] = {15, 7, 25, 3, 73, 32, 45};
    int size = sizeof(arr) / sizeof(arr[0]);
    int temp;

    // แสดงข้อมูลก่อนจัดเรียง
    printf("Old Series : ");
    for (int i = 0; i < size; i++) {
        printf("%d", arr[i]);
        if (i != size - 1) {
            printf(", ");
        }
    }
    printf("\n");

    // เริ่มการเรียงลำดับแบบ Bubble Sort
    for (int i = 0; i < size - 1; i++) {
        for (int j = 0; j < size - 1 - i; j++) {
            if (arr[j] > arr[j + 1]) {
                temp = arr[j];
                arr[j] = arr[j + 1];
                arr[j + 1] = temp;
            }
        }
    }

    // แสดงข้อมูลหลังจากจัดเรียงแล้ว
    printf("New Series : ");
    for (int i = 0; i < size; i++) {
        printf("%d", arr[i]);
        if (i != size - 1) {
            printf(", ");
        }
    }
    printf("\n");

    // ค้นหาตำแหน่งของหมายเลข 32
    int pos = -1;
    for (int i = 0; i < size; i++) {
        if (arr[i] == 32) {
            pos = i;
            break;
        }
    }

    // แสดงตำแหน่งของหมายเลข 32
    printf("Pos of 32 : %d\n", pos);

    return 0;
}
