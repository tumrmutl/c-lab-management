#include <stdio.h>

int check( int number_jum ) {
    if ( number_jum <= 1 ) return 0 ;
    for ( int i = 2 ; i * i <= number_jum ; i++ ) {
        if ( number_jum % i == 0 ) return 0 ;
    }
    return 1 ;
} // end function check

int main( ) {

    int count_array ;

    printf( "Input count array : " ) ;
    scanf( "%d", &count_array ) ;

    int input_number[ count_array ] ;
    int keep_array ;
    for ( int i = 0 ; i < count_array ; i++ ) {
        printf( "Input number array value[%d] : ", i ) ;
        scanf( "%d", &input_number[i] ) ;
    } // end for
    printf( "Index :" ) ;
    for ( int y = 0 ; y < count_array ; y++ ) {
        printf( " %d ", y ) ;
    } // end for 
    printf( "\n" ) ;
    printf( "Array :" ) ;
    for ( int u = 0 ; u < count_array ; u++ ) {
        if ( check( input_number[ u ] ) ) {
            printf( " %d ", input_number[ u ] ) ;
        } else {
            printf( " # " ) ;
        } // end if else
    } // end for
    
    return 0 ;
} // end main function