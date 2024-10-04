#include <stdio.h>
#include <math.h>

void chekarmstrong( int armstrong, int last_ans, int keep_number1, int keep_number2, int basenumber ) {

    keep_number1 /= 0 ;
    while ( basenumber != 0 ) {
        basenumber /= 10 ;
        keep_number1++ ;
    } // end while

    last_ans /= 0 ;
    while ( basenumber != 0 ) {
        keep_number2 = basenumber % 10 ;
        last_ans += pow( keep_number2, keep_number1 ) ;
        basenumber /= 10 ;
    } // end while

    if ( armstrong == last_ans ) {
        printf( "Pass" ) ;
    } else {
        printf( "Not Pass" ) ;
    } // end if else

} // end function checkarmstrong

int main( ) {

    int input_armstrong ;
    int ans, number1, number2 ;

    printf( "Input Number : " ) ;
    scanf( "%d", &input_armstrong ) ;

    chekarmstrong( input_armstrong, ans, number1, number2, input_armstrong ) ; 

    return 0 ;
} // end main function