#include <stdio.h>

int chek_grade( int scoregrade ) {
    if( scoregrade >= 80 && scoregrade <= 100 ) {
        printf( "A " ) ;
    } else if( scoregrade >= 75 && scoregrade < 80 ) {
        printf( "B+ " ) ;
    } else if( scoregrade >= 70 && scoregrade < 75 ) {
        printf( "B " ) ;
    } else if( scoregrade >= 65 && scoregrade < 70 ) {
        printf( "C+ " ) ;
    } else if( scoregrade >= 60 && scoregrade < 65 ) {
        printf( "C+ " ) ;
    } else if( scoregrade >= 55 && scoregrade < 60 ) {
        printf( "D+ " ) ;
    } else if( scoregrade >= 50 && scoregrade < 65 ) {
        printf( "D " ) ;
    } else if( scoregrade >= 0 && scoregrade <= 49) {
        printf( "F " ) ;
    } else {
        printf( "Error" ) ;
    } //end if else 
} // end function chek_grade

void generate_grade( int score1, int score2, int score3, int score4, int score5 ) {    
    chek_grade( score1 ) ;
    chek_grade( score2 ) ;
    chek_grade( score3 ) ;
    chek_grade( score4 ) ;
    chek_grade( score5 ) ;
} //end function generate_grade

void generate_averagescores( float xscore1, float xscore2, float xscore3, float xscore4, float xscore5 ) {    
    float sumscore ;
    sumscore = xscore1 + xscore2 + xscore3 + xscore4 + xscore5 ;
    sumscore = sumscore / 5 ;
    printf( "%.1f", sumscore ) ;
} //end function generate_averagescores

struct Student {
    char Name[ 20 ] ;
    char ID[ 5 ] ;
    float ScoreSub1 ;
    float ScoreSub2 ;
    float ScoreSub3 ;
    float ScoreSub4 ;
    float ScoreSub5 ;
} typedef Sd ;
//end struct Student

int main() {
    int limit = 3, i ;
    Sd Class[ limit ] ;

    for ( i = 0 ; i < limit ; i++ ) { 
        printf( "Student [ %d ]\n", i + 1 ) ;
        printf( "Insert name : " ) ;
        scanf( "%s", Class[ i ].Name ) ;
        printf( "Insert ID : " ) ;
        scanf( "%s", Class[ i ].ID ) ;
        printf( "Insert Scores in Subject 1 : " ) ;
        scanf( "%f", &Class[ i ].ScoreSub1 ) ; 
        printf( "Insert Scores in Subject 2 : " ) ;
        scanf( "%f", &Class[ i ].ScoreSub2 ) ;
        printf( "Insert Scores in Subject 3 : " ) ;
        scanf( "%f", &Class[ i ].ScoreSub3 ) ;
        printf( "Insert Scores in Subject 4 : " ) ;
        scanf( "%f", &Class[ i ].ScoreSub4 ) ;
        printf( "Insert Scores in Subject 5 : " ) ;
        scanf( "%f", &Class[ i ].ScoreSub5 ) ;
    } //end loop for

    printf( "Student Details" ) ;
    for ( i = 0 ; i < limit ; i++ ) {
        printf( "\nStudent [ %d ] \n", i + 1 ) ;
        printf( "Name : %s\n", Class[ i ].Name ) ;
        printf( "ID : %s\n", Class[ i ].ID ) ;
        printf( "Scores : %.0f %.0f %.0f %.0f %.0f \n",
               Class[ i ].ScoreSub1, Class[ i ].ScoreSub2, 
               Class[ i ].ScoreSub3, Class[ i ].ScoreSub4, Class[ i ].ScoreSub5 ) ;
        printf( "Grade : " ) ;
        generate_grade( Class[ i ].ScoreSub1, Class[ i ].ScoreSub2, 
                        Class[ i ].ScoreSub3, Class[ i ].ScoreSub4, Class[ i ].ScoreSub5) ;
        printf( "\n" ) ;
        printf( "Average Scores : " ) ;
        generate_averagescores( Class[ i ].ScoreSub1, Class[ i ].ScoreSub2, 
                        Class[ i ].ScoreSub3, Class[ i ].ScoreSub4, Class[ i ].ScoreSub5) ;
        printf( "\n" ) ;
    } //end loop for

    return 0 ;
} //end main function
