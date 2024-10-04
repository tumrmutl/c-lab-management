#include <stdio.h>
#include <string.h>
#include <ctype.h>

void to_lowercase( char str[ ] ) {
    for ( int x = 0; str[ x ]; x++ ) {
        str[ x ] = tolower( str[ x ] ) ;
    }//end for
}//end function


int is_palindrome( char str[ ] ) {
    int c = strlen( str ) ;
    int begin = 0 ;
    int end = c - 1 ;

    while ( begin < end ) {
        if ( str[ begin ] != str[ end ] ) {
            return 0 ; 
        }//end if
        begin++ ;
        end-- ;
    }//end while
    return 1 ; 
}

int main() {
    char str[ 100 ] ;

    printf( "Enter word: " ) ;
    scanf( "%s", str ) ;


    to_lowercase( str ) ;

    if ( is_palindrome( str ) ) {
        printf( "Pass.\n" ) ;
    } else {
        printf( "Not Pass.\n" ) ;
    }//end if else

    return 0 ;
}//end function