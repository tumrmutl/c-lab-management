#include <stdio.h>


int main() {
    char  employeesID[10]
    int hrs, salary ;
    int month = 24 ;
    float money ; 
    
    printf( "Input the employees ID(Max. 10 chars): " ) ;
    scanf( "%s", employeesID ) ;
    printf( "Input the workingv hrs: " ) ;
    scanf( "%d", &hrs ) ;
    printf( "salary amount/hr: " ) ;
    scanf( "%d", &salary ) ;
    
    money = hrs * salary * month ;
    
    printf( "-----------------------------------------------------------/n" ) ;
    printf( "employees ID = %s /n", employeesID ) ;
    printf( "salary = U$ %.2f" , money ) ;
    printf( "/n------------------------------------------------------------" ) ;
    
     return 0 ;
}//end main function
