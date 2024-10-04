#include <stdio.h>

int main() {

    char ID[ 10 ] ;
    int WorkingHours ;
    int Salary ;
    
    printf( "Input the employees ID [Max. 10 char] : " ) ;
    scanf( "%s", ID ) ;
    printf( "Input the working hrs : " ) ;
    scanf( "%d", &WorkingHours ) ;
    printf( "Salary amount/hr : " ) ;
    scanf( "%d", &Salary ) ;
    
    printf( "\n\n" ) ;
    printf( "Employees ID = %s\n", ID ) ;
    printf( "Salary = %d THB", WorkingHours * Salary * 22 ) ;
    
    printf( "\n\n" ) ;
    
    return 0 ;
    
}//end main function
