#include <stdio.h>

int calculator_1( int b, int e ) {
    int r = 1 ;
    for ( int i = 0 ; i < e ; i++ ) {
        r *= b ;
    }//end for
    return r ;
}//end function

void calculator_2( int n ) {
    int o = n ;
    int s = 0 ;
    int d = 0 ;

    int t = n ;
    while ( t != 0 ) {
        t /= 10 ;
        d++ ;
    }//end while

    t = n ;
    while ( t != 0 ) {
        int x = t % 10 ;
        s += calculator_1( x , d ) ;  
        t /= 10 ;
    }//end while

    if ( s == o ) {
        printf( "Pass.\n" ) ;
    } else {
        printf( "Not Pass.\n" ) ;
    }//end if else
}//end function

int main() {
    int n ;

    printf( "Enter Number: " ) ;
    scanf( "%d", &n ) ;

    calculator_2( n ) ;

    return 0 ;
}//end function