#include <stdio.h>
#include <string.h>

int main() {
    
    char word[ 100 ] ;
    
    printf( "Enter word : " ) ;
    scanf( "%s", word ) ;
    
    int start = 0 ;
    int end = strlen( word ) - 1 ;
    
    while( start < end ) {
        if( word[ start ] >= 'A' && word[ start ] <= 'Z' ) { // ถ้าตัวอักษรเป็นพิมพ์ใหญ่ แปลงเป็นพิมพ์เล็ก
            word[ start ] = word[ start ] + 32 ;
        }//end if
        if( word[ end ] >= 'A' && word[ end ] <= 'Z' ) {     // ถ้าตัวอักษรเป็นพิมพ์ใหญ่ แปลงเป็นพิมพ์เล็ก
            word[ end ] = word[ end ] + 32 ;
        }//end if
        
        if( word[ start ] != word[ end ] ) {
            printf( "Not pass.\n" ) ;
            return 0 ;
        }//end if
        
        start++ ;
        end-- ;
        
    }//end while
    
    printf( "Pass.\n" ) ;

    return 0 ;
    
}//end function
