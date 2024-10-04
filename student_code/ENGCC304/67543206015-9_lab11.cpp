#include <stdio.h>

int power( int base , int exp ) {
    int result = 1 ;
    for ( int i = 0 ; i < exp ; i++ ) {
        result *= base ;
    }
    return result ;
}

int main() {
    int number, originalNumber, remainder, result = 0 , n = 0 ;

    // รับค่าจากผู้ใช้
    printf( "Enter Number: " ) ;
    scanf( "%d" , &number ) ;

    originalNumber = number ;

    // นับจำนวนหลักของตัวเลข
    while ( originalNumber != 0 ) {
        originalNumber /= 10 ;
        ++n ;
    }

    originalNumber = number ;

    // คำนวณผลรวมของเลขยกกำลังตามจำนวนหลัก
    while ( originalNumber != 0 ) {
        remainder = originalNumber % 10 ;
        result += power( remainder, n ) ;
        originalNumber /= 10 ;
    }

    // ตรวจสอบว่าเป็นตัวเลขอาร์มสตรองหรือไม่
    if ( result == number ) {
        printf( "Pass.\n" ) ;
    } else {
        printf( "Not Pass.\n" ) ;
    }

    return 0 ;
}
