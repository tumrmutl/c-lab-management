#include <stdio.h>
#include <stdlib.h>
#include <time.h>

int main() {
    
    int number_score  = 100 ; //คะเเนนผู้เล่น
    int number_input = 0 ; //ค่าที่รับเข้ามา
    int number_play = 0 ; //ค่าที่รับเข้ามาใช้เฉพาะเมนู1 กับ -1
    int hight_score = 1 ; //ค่าน้อย 1
    int low_score  = 100 ; //ค่ามาก 100
    
    do //loop meun game
    {
        printf ( " Do you want to play game (1 = play , -1 = exit ) : \n" ) ;
        scanf( "%d" , &number_play ) ;
        
        if ( number_play == -1 ) ;
         printf( "exit" ) ;
    }  
    while( number_play != 1 ) ;
       printf( "hight" ) ; 
       
    do 
    {
        int A = rand () %100 + 1 ; // สูตรการหารเอาเศษ
        printf( " score % d " , number_score ) ;
        
        do
        {
            printf( "Guess the winning number ( %d - %d ) : \n" , hight_score  , low_score ) ;
            scanf( "%d" , &number_input ) ; 
            if( number_input == A ) 
            {
                printf( "That is correct ! the winning number is %d \n" , A ) ;
                printf( "score this game : %d \n" , number_score ) ; 
            } else {
                number_score = number_score - 10 ;
                if ( number_input < A ) {
                    hight_score = number_input + 1 ;
                    printf( "sorry number is higher than ( %d ) , ( score = %d )\n" , hight_score , number_score) ;
                    
                } else {
                    low_score = number_input - 1 ;
                    printf( "sorry number is lower than ( %d ) ,( score = %d \n )" , low_score,  number_score) ;
                }
                
            }
            
        }
        while( number_input != A ) ;
            printf( "play game ( 1 = play , -1 = exit ) : \n" ) ;
            scanf( "%d , number_play" ) ;
            hight_score = 100 ;
            low_score = 1 ;
            number_score = 100 ;
            if( number_play == -1 ) {
              exit(0) ;
            }
    } while (number_play == 1 );
    
}
