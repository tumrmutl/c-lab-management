#include <stdio.h>
int main() {
   
    int score = 0 ; 
    printf( "enter score" ) ;

    if (scanf( "%d", &score ) != 1 ) {
        printf( "Please enter number only.\n" ) ;
        return 1 ;
    }
   
    switch ( score/5 ) {
    case  20 :
      printf( "A!" ) ;
      break ;
    case  18 :
      printf( "A!" ) ;
      break ;
    case  16 :
      printf( "A!" ) ;
      break ;
    case  15 :
      printf( "B+!" ) ;
      break ;
    case  14 :
      printf( "B!" ) ;
      break ;
    case  13 :
      printf( "C+!" ) ;
      break ;
    case  12 :
      printf( "C!" ) ;
      break ;
    case  11 :
      printf( "D+!" ) ;
      break ;
    case  10 :
      printf( "D!" ) ;
      break ;
    case  9 :
      printf( "F!" ) ;
      break ;
    case  8 :
      printf( "F!" ) ;
      break ;
    case  7 :
      printf( "F!" ) ;
      break ;
    case  6 :
      printf( "F!" ) ;
      break ;
    case  5 :
      printf( "F!" ) ;
      break ;
    case  4 :
      printf( "F!" ) ;
      break ;
    case  3 :
      printf( "F!" ) ;
      break ;
    case  2 :
      printf( "F!" ) ;
      break ;
    case  1 :
      printf( "F!" ) ;
      break ;
    case  0 :
      printf( "F!" ) ;
      break ;
    default :
      printf( "enter score number only." ) ;
      break ;
    }
}   
