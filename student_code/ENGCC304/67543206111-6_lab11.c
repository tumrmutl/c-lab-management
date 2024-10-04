//จงเขียนโปรแกรมเพื่อรับคำจากผู้ใช้งาน เพื่อตรวจสอบว่า คำที่กรอกมามีลักษณะเป็นคำหรือวลีที่สามารถอ่านจากหลังไปหน้าหรือหน้าไปหลังแล้วยังคงความหมายเหมือนเดิมได้
    //โดยที่ หากคำนั้นสามารถอ่านจากหน้าไปหลังหรือหลังไปได้ ให้แสดงผลลัพธ์ว่า Pass แต่หากทำไม่ได้ให้ขึ้นว่า Not Pass//


#include <stdio.h>
#include <string.h>

int main() {
    char input[100] ;
    int length , i ;
    int palindrome = 1 ;

    printf("Enter world : ") ;
    fgets(input, sizeof(input), stdin) ;

    input[strcspn(input, "\n")] = '\0' ;
    length = strlen(input) ;  

    for (i = 0 ; i < length / 2 ; i++) {
        if (input[i] != input[length - 1 - i]) {
            palindrome = 0 ; 
            break ;
        }
    }

    if (palindrome) {
        printf("Pass\n") ;
    } else {
        printf("Not Pass\n") ;
    }

    return 0 ;
}
