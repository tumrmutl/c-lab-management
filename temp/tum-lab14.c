#include <stdio.h>
#include <limits.h>  // สำหรับใช้ค่าคงที่ INT_MAX และ INT_MIN

void findMinMax(int arr[], int size, int *min, int *max) {
    *min = INT_MAX;
    *max = INT_MIN;
    
    // วนลูปเพื่อหาค่ามากที่สุดและค่าน้อยที่สุด
    for (int i = 0; i < size; i++) {
        if (arr[i] < *min) {
            *min = arr[i];
        }
        if (arr[i] > *max) {
            *max = arr[i];
        }
    }
}

int main() {
    int arr[100], size = 0, min, max;
    char input[1000];
    
    // รับข้อมูลจากผู้ใช้เป็นสตริง
    printf("Enter value: ");
    fgets(input, sizeof(input), stdin);  // รับค่าที่ผู้ใช้ป้อน
    
    // แปลงสตริงเป็นตัวเลขและเก็บในอาร์เรย์
    char *ptr = input;
    while (sscanf(ptr, "%d", &arr[size]) == 1) {
        size++;
        while (*ptr != ' ' && *ptr != '\0') {  // ข้ามช่องว่างเพื่อหาค่าถัดไป
            ptr++;
        }
        if (*ptr == ' ') {
            ptr++;
        }
    }
    
    // แสดง Index และ Array
    printf("Index: ");
    for (int i = 0; i < size; i++) {
        printf("%2d ", i);
    }
    printf("\nArray: ");
    for (int i = 0; i < size; i++) {
        printf("%2d ", arr[i]);
    }
    printf("\n");
    
    // ค้นหาค่ามากที่สุดและค่าน้อยที่สุด
    findMinMax(arr, size, &min, &max);
    
    // แสดงผลลัพธ์
    printf("\nMin : %d\n", min);
    printf("Max : %d\n", max);
    
    return 0;
}
