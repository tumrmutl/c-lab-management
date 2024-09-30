#include <stdio.h>

int main() {
    char employeeID[11];  // กำหนดความยาวสูงสุดของ employeeID 10 ตัวอักษร + 1 สำหรับ '\0'
    int workingHours;
    float salaryPerHour, totalSalary;

    // รับข้อมูลพนักงาน
    printf("Input the Employees ID (Max. 10 chars): ");
    scanf("%s", employeeID);

    printf("Input the working hrs: ");
    scanf("%d", &workingHours);

    printf("Salary amount/hr: ");
    scanf("%f", &salaryPerHour);

    // คำนวณรายได้รวม
    totalSalary = workingHours * salaryPerHour;

    // แสดงผลลัพธ์
    printf("Employees ID = %s\n", employeeID);
    printf("Salary = U$ %.2f\n", totalSalary);

    return 0;
}
