
/*
    ผู้ใช้กรอกค่า "คะแนนดิบ" เข้ามาในระบบเพื่อต้องการตรวจสอบเกรดในรายวิชา Programming ซึ่งมีเกณฑ์การให้คะแนนคือ F อยู่ในช่วงคะแนนน้อยกว่า 50 , D อยู่ในช่วงระหว่าง 50 ถึง 55 , D+ อยู่ในช่วงระหว่าง 55 ถึง 60 , C อยู่ในช่วงระหว่าง 60 ถึง 65 , C+ อยู่ในช่วงระหว่าง 65 ถึง 70 , B อยู่ในช่วงระหว่าง 70 ถึง 75 , B+ อยู่ในช่วงระหว่าง 75 ถึง 80 , A อยู่ในช่วงคะแนนมากกว่า 80 ขึ้นไป
    (กำหนดให้ข้อนี้ใช้คำสั่ง if else ได้เท่านั้น)
    
    Test case:
        enter score :
            80
    Output:
        A !

    Test case:
        enter score :
            55
    Output:
        D+ !

    Test case:
        enter score :
            64
    Output:
        C !

    Test case:
        enter score :
            44
    Output:
        F !

    Test case:
        enter score :
            hello
    Output:
        please enter number only.
*/

#include <stdio.h>
int main() {
    int score ;
    int term ;

    printf( "Enter score: " ) ;

    term = scanf( "%d" , &score ) ;
    if ( term != 1 ) {
        printf( "please enter number only.\n" ) ;
    } else {
        if (score < 50) {
            printf( "F !\n") ;
        } else if ( score >= 50 && score < 55 ) {
            printf( "D !\n" ) ;
        } else if ( score >= 55 && score < 60 ) {
            printf( "D+ !\n" ) ;
        } else if ( score >= 60 && score < 65 ) {
            printf( "C !\n" ) ;
        } else if ( score >= 65 && score < 70 ) {
            printf( "C+ !\n" ) ;
        } else if ( score >= 70 && score < 75 ) {
            printf( "B !\n" ) ;
        } else if ( score >= 75 && score < 80 ) {
            printf( "B+ !\n" ) ;
        } else if ( score >= 80 ) {
            printf( "A !\n" ) ;
        }//end if
    }//end else
    return 0;
}//end main function
#include <stdio.h>


