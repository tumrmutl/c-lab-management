#include <stdio.h>

int main() {
    char ID[ 10 ] ;
    int WorkingHour, Salary ;
    printf( "\n\nInput the Employees ID(Max. 10 chars): " ) ;
    scanf( "%s", ID ) ;
    printf( "Input the working hrs: " ) ;
    scanf( "%d", &WorkingHour ) ;
    printf( "Salary amount/hr: " ) ;
    scanf( "%d", &Salary ) ;

    printf( "\n---------------\n" ) ;
    printf( "Employees ID = %s\n", ID ) ;
    printf( "Salary = %d THB", WorkingHour * Salary * 22 ) ;

    printf( "\n\n" ) ;
    return 0 ;
}//end function