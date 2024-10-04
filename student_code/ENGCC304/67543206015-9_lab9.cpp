#include <stdio.h>

int main() {
    int Number ;
    
    // รับค่าขนาดของอาเรย์จากผู้ใช้
    printf( "Enter N :\n" ) ;
    scanf( "%d" , & Number ) ;
    
    int array[ Number ] ;
    
    // รับค่าตัวเลขเข้ามาในอาเรย์
    for( int i = 0 ; i < Number ; i++ ) {
        printf( "Enter value[ %d ] :\n" , i ) ;
        scanf( "%d", &array[ i ] ) ;
    }
    
    // แสดงผลลัพธ์
    printf( "Index:  " ) ;
    for(int i = 0; i < Number; i++) {
        printf( "%d  " , i ) ;
    }
    printf( "\n Array :  " ) ;
    
    for( int i = 0 ; i < Number ; i++ ) {
        int num = array[ i ] ;
        int isPrime = 1 ;  // 1 คือจำนวนเฉพาะ
        
        if ( num < 2 ) {
            isPrime = 0 ;
        } else {
            for ( int j = 2 ; j <= num / 2 ; j++ ) {
                if ( num % j == 0 ) {
                    isPrime = 0 ;  // ไม่เป็นจำนวนเฉพาะ
                    break ;
                }
            }
        }
        
        // ถ้าเป็นจำนวนเฉพาะให้แสดงตัวเลข, ถ้าไม่เป็นให้แสดง #
        if ( isPrime ) {
            printf( "%d  " , num) ;
        } else {
            printf( "#  " ) ;
        }
    }

    return 0 ;
}
