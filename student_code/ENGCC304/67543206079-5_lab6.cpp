#include <stdio.h>
int main() {
    int n ;
    printf( "Enter your number:" ) ;
    scanf( "%d", &n ) ;
    for ( int i = 0 ; i < n ; i++ ) {
        for ( int j = 0 ; j < n ; j++ ) {
            if ( n % 2 == 0 ) {
                printf( "%d ", i == j ) ;
            } else {
                printf( "%d ", j == n - i - 1 ) ;
            }//end if
        }//end for
        printf( "\n" ) ;
    }//end for
    return 0 ;
}// end main