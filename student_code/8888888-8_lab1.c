#include <stdio.h>
int main() {
    float cc ;
    printf( "\n\nEnter C : " ) ;
    scanf( "%f", &cc ) ;
    printf( "%.2f 'C = %.2f 'F \n\n", cc, ( 1.80 * cc ) + 32 ) ;
    return 0 ;
}//end function