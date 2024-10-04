#include <stdio.h>

int main() {
    
    char empID[10] ;
    int work_hr, saraly ;
    float total ;

    printf( "Input the Employees ID : " ) ; 
    scanf( "%s", empID ) ;
    printf( "Input the working hour : " ) ; 
    scanf( "%d", &work_hr ) ;
    printf( "Input Salary amount/hour : " ) ; 
    scanf( "%d" , &saraly ) ;

    total = work_hr * saraly ;
    
    printf( "\n\nEmployees ID : %s \n", empID ) ; 
    printf( "Salary : U$ %.2f \n", total ) ;

    return 0 ;
}