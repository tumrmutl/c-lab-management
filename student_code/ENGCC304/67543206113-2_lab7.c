#include <stdio.h>
#include<stdlib.h>
#include <time.h>

void play_game() ;

int main() {
    
    int c ;
    
    while ( 1 ) {
        printf( "Do you want to play game (1=play,-1=exit): " ) ;
        scanf( "%d" , &c ) ;
        
        if ( c == 1 ) {
            play_game() ;
        } else if ( c == -1 ) {
            printf( "Goodbye!\n" ) ;
            break ;
        } else {
            printf( "1 or -1\n" ) ;
        }//end if else
    }//end while
    return 0 ;
}//end function

void play_game() {
    
    int random_number , answer , points = 100 ;
    int low = 1 , high = 100 ;
    
    srand( time( NULL ) ) ; 
    random_number = ( rand() % 100 ) + 1 ;
    
    printf( "(Score=%d)\n", points ) ;

    while( 1 ) {
        printf( "Guess the winning number (%d-%d): ", low , high ) ;
        scanf( "%d", &answer ) ;

        if ( answer == random_number ) {
            printf( "That is correct! The winning number is %d.\n" , random_number ) ;
            printf( "Score this game: %d\n" , points ) ;
            break ;
        } else {
            points -= 10 ;
            if ( points <= 0 ) {
                printf( "Game over!\n" ) ;
                break ;
            }
            if ( answer < random_number ) {
                printf( "Sorry, the winning number is HIGHER than %d. (Score=%d)\n" , answer, points ) ;
                if ( answer < low ){
                    low = low ;
                } else {
                    low = answer + 1 ;
                }
            } else {
                printf( "Sorry, the winning number is LOWER than %d. (Score=%d)\n" , answer, points ) ;
                if ( answer > high ){
                    high = high ;
                } else {
                    high = answer + 1 ;
                }
            }//end if else
        }//end if else
    }//end while
}//end play_game function
