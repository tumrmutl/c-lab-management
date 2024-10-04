#include <stdio.h>

int main() {
    
    int score = 0 ;
    printf( "Please enter score : " ) ;
    if( scanf ("%d", &score) != 1 ) {
        printf( "Please enter number only." ) ;
        return 1 ;
    }//end if
    
    if( score >= 80 ) {
        printf( "A !" ) ;
    } else if( score >= 75 && score < 80 ) {
        printf( "B+ !" ) ;
    } else if( score >= 70 && score < 75 ) {
        printf( "B !" ) ;
    } else if( score >= 65 && score < 70 ) {
        printf( "C+ !" ) ;
    } else if( score >= 60 && score < 65 ) {
        printf( "C !" ) ;
    } else if( score >= 55 && score < 60 ) {
        printf( "D+ !" ) ;
    } else if( score >= 50 && score < 55 ) {
        printf( "D !" ) ;
    } else {
        printf( "F !" ) ;
    }//end if

    return 0 ;

}//end main function









#include <stdio.h>

int main() {
    
    int score = 0 ;
    printf( "Please enter score : " ) ;
    
    if(scanf( "%d", &score ) != 1 ) {
        printf( "Please enter number only." ) ;
        return 1 ;
    }

    switch( score / 5 ) {
    case 20 :
    case 19 :
    case 18 :
    case 17 :
    case 16 :
        printf( "A !" ) ;
        break ;
    case 15 :
        printf( "B+ !" ) ;
        break ;
    case 14 :
        printf( "B !" ) ;
        break ;
    case 13 :
        printf( "C+ !" ) ;
        break ;
    case 12 :
        printf( "C !" ) ;
        break ;
    case 11 :
        printf( "D+ !" ) ;
        break ;
    case 10 :
        printf( "D !" ) ;
        break ;
    case 9 :
    case 8 :
    case 7 :
    case 6 :
    case 5 :
    case 4 :
    case 3 :
    case 2 :
    case 1 :
    case 0 :
        printf( "F !" ) ;
        break ;
    default :
        printf( "please enter number only 0 - 100." ) ;
        break ;
    }

    return 0 ;
}//end main function
