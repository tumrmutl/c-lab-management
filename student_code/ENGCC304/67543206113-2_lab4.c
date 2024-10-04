#include <stdio.h>

int main() {
    char empID[11] ;
    float workingHours, salaryPerHour, totalSalary ;

    printf( "Input the Employees ID (Max. 10 chars): \n" ) ;
    scanf( "%10s" , empID ) ;
    printf( "Input the working hrs: \n" ) ;
    scanf( "%f" , &workingHours ) ;
    printf( "Salary amount/hr: \n" ) ;
    scanf( "%f" , &salaryPerHour ) ;

    totalSalary = workingHours * salaryPerHour ;

    printf( "Employees ID = %s\n" , empID ) ;
    printf( "Salary = U$ %.2f\n" , totalSalary ) ;

    return 0;
}//end function