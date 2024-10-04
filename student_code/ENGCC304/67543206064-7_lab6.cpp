#include <stdio.h>

int main() {
    int input_num = 0 ;
    printf( "Please enter line : " ) ;
    scanf( "%d", &input_num ) ;
    for( int i = 0 ; i < input_num ; i++ ) {
        printf( "\n" ) ;
        if (input_num % 2 == 0) {
            for( int j = 0 ; j < input_num ; j++ ) {
                if( j == i ) {
                    printf( "1 " ) ;
                } else {
                    printf( "0 " ) ;  
                }//end if
            }//end for
        }else {
            for (int a = 0 ; a < input_num ; a++) {
                if( i == input_num - a - 1 ) {
                    printf( "1 " ) ;
                } else {
                    printf( "0 " ) ;
                }//end if
            }//end for
        }//end if
    }//end for
    return 0 ;
}//end function
