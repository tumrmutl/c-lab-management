#include <stdio.h>
#include <math.h>

int isArmstrong ( int num ) {
    int originalNum , remainder , result = 0 , n = 0 ;

    originalNum = num ;

    while ( originalNum != 0 ) {
        originalNum /= 10 ;
        ++n ;
    }//end while

    originalNum = num ;

    while ( originalNum != 0 ) {
        remainder = originalNum % 10 ;
        result += pow ( remainder , n ) ;
        originalNum /= 10 ;
    }//end while

    return ( result == num ) ;
}//end int isArmstrong

int main() {

    int nm ;

    printf ( "Enter Number: " ) ;
    scanf ( "%d" , &nm ) ;

    if ( isArmstrong(nm) ) {
        printf ( "Pass.\n") ;
    } else { 
        printf ( "Not Pass.\n" ) ;
    }//end else

    return 0 ;
}//end main function