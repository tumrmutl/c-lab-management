#include <stdio.h>
#include <string.h>
#include <ctype.h>

int main( ) {

    char word[ 50 ] ;

    printf( "Input word : " ) ;
    fgets( word, sizeof( word ), stdin ) ;

    int left = 0 ;
    int right = strlen( word ) - 1 ;

    while ( left < right ) {

        while ( left < right && !isalnum( word[ left ] ) ) left++ ; // end while
        while ( left < right && !isalnum( word[ right ] ) ) right-- ; // end while

        if ( tolower( word[ left ] ) != tolower( word[ right ] ) ) {

            printf( "Not Pass" ) ;

            return 0 ; // end function
        } // end if

        left++ ;
        right-- ;
    } // end while

    printf( "Pass" ) ;
    return 0 ; // end function

} //end main function
