#include <stdio.h>
#include <math.h>                // สำหรับใช้ฟังก์ชัน pow()

int isArms( int num ) ;          // PROTOTYPE

int main() {
    
    int num ;
    
    printf( "Enter Number : " ) ;
    scanf( "%d", &num ) ;

    if ( isArms( num ) ) {
        printf( "Pass.\n" ) ;
    } else {
        printf( "Not Pass.\n" ) ;
    }//end if

    return 0 ;
    
}//end function main

int isArms( int num ) {         // ฟังก์ชันสำหรับตรวจสอบว่าเป็นตัวเลขอาร์มสตรอง
    int origNum = num ;
    int find, result = 0 ;
    int n = 0 ;

    int digit = num ;           // หาจำนวนหลักของตัวเลข
    while ( digit != 0 ) {
        digit /= 10 ;
        n++ ;
    }//end while

    digit = origNum ;                        // คำนวณผลรวมของเลขยกกำลังของแต่ละหลัก
    while ( digit != 0 ) {
        find = digit % 10 ;
        result += pow( find, n ) ;           // ยกกำลังตามจำนวนหลัก (find กำลัง n)
        digit /= 10 ;
    }//end while
    
    return ( result == origNum ) ;     // คืนค่า 1 ถ้าเป็นอาร์มสตรอง, 0 ถ้าไม่ใช่
    
}//end function isArms
