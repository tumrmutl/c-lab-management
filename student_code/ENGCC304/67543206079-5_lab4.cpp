#include <stdio.h>

int main() {
    char EmployeesID[11] ;
    int workinghrs ;
    float Salaryamount, Salarytotal ;

    printf( "Input the Employees ID(Max. 10 chars): \n" ) ;
    scanf( "%10s", EmployeesID ) ;
    printf( "Input the working hours: \n" ) ;
    scanf( "%d", &workinghrs ) ;
    printf( "Input Salary amount (hourly rate): \n" ) ;
    scanf( "%f", &Salaryamount ) ;

    Salarytotal = workinghrs * Salaryamount ;

    printf( "Employees ID = %s\n", EmployeesID ) ;
    printf( "Total Salary = %.2f\n", Salarytotal ) ;

    return 0 ;
}//end main
