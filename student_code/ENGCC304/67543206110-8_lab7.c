#include <stdio.h>
#include <stdlib.h>
#include <time.h>

int main() {

    srand(time(NULL)) ;  

    int toplay = 1 , score = 100 , min = 1 , max = 100 , rand_number , answer ;

    do {
        rand_number = rand() % 100 + 1 ;  
        score = 100 ;
        min = 1 ;
        max = 100 ;

        printf( "Do you want to play game (1=play, -1=exit) : " ) ;
        scanf( "%d" , &toplay ) ;

        if ( toplay != 1 ) {
            break ;  
        }//end if

        printf( "( Score = %d )\n" , score ) ;

        do {
            printf( "Guess the winning number ( %d - %d ): " , min , max ) ;
            scanf( "%d" , &answer );

            if ( answer == rand_number ) {
                printf( "That is correct! The winning number is %d. \n", rand_number ) ;
                printf( "Score this game: %d \n" , score ) ;
                break; 
            } else if ( answer < rand_number ) {
                score -= 10 ;  
                printf( "Sorry, the winning number is HIGHER than %d. ( Score = %d )\n" , answer , score ) ;
                min = ( answer + 1 > min ) ? answer + 1 : min ; 
            } else {
                score -= 10 ;
                printf( "Sorry, the winning number is LOWER than %d. ( Score = %d )\n" , answer , score ) ;
                max = ( answer - 1 < max ) ? answer - 1 : max ; 
            if (score <= 0) {
                printf( "Game over!\n" )  ;
            }//end else

                break ;  
            }//end if

        } while ( score > 0 ) ; 

    } while ( toplay == 1 ) ;  

    return 0 ;
}