#include <stdio.h>
int main() {
    char word[ 100 ] ;
    int i = 0 , j = 0 , isPalindrome = 1 ;

    // รับคำจากผู้ใช้
    printf( "Enter word: " ) ;
    scanf( "%s" , word ) ;

    // แปลงตัวอักษรทั้งหมดเป็นตัวพิมพ์เล็ก
    while ( word[ i ] ) {
        if ( word[ i] >= 'A' && word[ i ] <= 'Z' ) {
            word[ i ] = word[ i ] + 32 ; // แปลงตัวพิมพ์ใหญ่เป็นตัวพิมพ์เล็ก
        }
        i++;
    }

    // ตรวจสอบว่าเป็นคำที่สามารถอ่านได้จากหน้าไปหลังหรือไม่
    for ( j = 0 ; j < i / 2 ; j++ ) {
        if ( word[ j ] != word [ i - j - 1 ] ) {
            isPalindrome = 0 ; // ถ้าไม่ตรงกันตั้งค่าเป็น 0
            break ;
        }
    }

    // แสดงผลลัพธ์
    if (isPalindrome) {
        printf( "Pass.\n" ) ;
    } else {
        printf( "Not Pass.\n" ) ;
    }

    return 0 ;
}
