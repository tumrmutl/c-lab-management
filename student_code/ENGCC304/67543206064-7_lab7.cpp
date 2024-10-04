#include <stdio.h>
#include <stdlib.h> //ต้องเพิ่มไลบรารีนี้เพื่อใช้ rand()
#include <time.h> //ต้องเพิ่มไลบรารีนี้เพื่อใช้ time()

int main() {
    
    int choice, score, winnum, guess ;
    int lowernum, uppernum ;
    
    srand ( time ( NULL ) ) ; //สุ่มตัวเลขตามเวลาปัจจุบัน ถ้าไม่ใช้เลขที่สุ่มจะซ้ำ
    
    while ( 1 ) {
        printf( "Do you want to play game ( 1 = play, -1 = exit ) : " ) ;
        scanf( "%d", &choice ) ;
        
        if ( choice == 1 ) {
            score = 100 ;
            lowernum = 1 ;
            uppernum = 100 ;
            winnum = rand() % 100 + 1 ; //สุ่มเลข 1 - 100 การใช้ % 100 ทำให้ได้ค่าภายในช่วง 0 ถึง 99 การบวก 1 ทำให้ค่าที่สุ่มได้เปลี่ยนไปอยู่ในช่วง 1 ถึง 100
            
            printf( "Score = 100\n" ) ;
            
            while ( score > 0 ) {
                printf( "Guess the winning number ( %d - %d ) : ", lowernum, uppernum ) ;
                scanf( "%d", &guess ) ;
                
                if ( guess <= uppernum && guess >= lowernum ) {
                    
                    if ( guess > winnum ) {
                        score -= 10 ;
                        uppernum = guess - 1 ;
                        printf( "Sorry, the winning number is LOWER than %d. ( Score = %d )\n", guess, score ) ;
                    } else if ( guess < winnum ) {
                        score -= 10 ;
                        lowernum = guess + 1 ;
                        printf( "Sorry, the winning number is HIGHER than %d. ( Score = %d )\n", guess, score ) ;
                    } else {
                        printf( "That is correct! The winning number is %d.\n", winnum ) ;
                        printf( "Score this game : %d\n", score ) ;
                        break ; //ถ้าไม่ใช้จะวนลูป while (บรรทัด 76 ลงมา)
                    }//end if
                    
                } else {
                    printf( "Please guess a number between %d and %d.\n", lowernum, uppernum ) ;
                }//end if
                
                if ( score == 0 ) {
                    printf( "Game over! ( Score = 0 )\n" ) ;
                }//end if
            
            }//end while
            
        } else if ( choice == -1 ) {
            printf( "Exit the game.\n" ) ;
            break ;
            
        } else {
            printf( "Please enter number only 1 (play) and -1 (exit)\n" ) ;
        }//end if
        
    }//end while

    return 0 ;
}//end function
