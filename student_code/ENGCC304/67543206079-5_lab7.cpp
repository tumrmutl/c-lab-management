#include <stdio.h>
#include <stdlib.h>
#include <time.h>

int main() {
    int n, i, Again = 1, min, max ;
    int Score ;

    srand( time (NULL) ) ;  
    printf( "Do you want to play again (1=play, -1=exit) : " ) ;
    scanf( "%d", &Again ) ;
    while ( Again != -1 ) {
        n = rand() % 100 + 1 ;  
        min = 1 ;  
        max = 100 ;  
        Score = 100 ; 

        while ( Score > 0 ) {
            printf( "Guess the winning number (%d-%d): ", min, max ) ;
            scanf( "%d", &i ) ;

            if ( i < min || i > max ) {
                Score -= 10 ;
                printf( "Please enter number %d - %d. (Score=%d )\n", min, max, Score ) ;
            } else if ( i == n ) {
                printf( "Correct! The number was %d.\n", n ) ;
                printf( "Score this game: %d\n", Score ) ;
                break ;
            } else if ( i > n ) {
                Score -= 10 ;
                printf( "Sorry, the winning number is LOWER than %d (Score=%d )\n", i, Score ) ;
                max = i - 1;  
            } else if ( i < n ) {
                Score -= 10 ;
                printf( "Sorry, the winning number is HIGHER than %d (Score=%d)\n", i, Score ) ;
                min = i + 1 ;  
            }

        
            if ( Score <= 0 ) {
                printf( "Game over! The number was %d.\n", n ) ;
                printf( "Your final score is 0.\n" ) ;  
            }
        }//end while Score

    
        printf( "Do you want to play again (1=play, -1=exit) : " ) ;
        scanf( "%d", &Again ) ;
    }//end while Again
    return 0 ;
}//end main
