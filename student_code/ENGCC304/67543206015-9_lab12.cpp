#include <stdio.h>

struct Student {
    char Name[ 20 ] ;
    char ID[ 5 ] ;
    float ScoreSub1 ;
    float ScoreSub2 ;
    float ScoreSub3 ;
    float ScoreSub4 ;
    float ScoreSub5 ;
} typedef S ;

int main() {
    S students[ 3 ] ; // สร้างอาร์เรย์นักเรียน 3 คน
    int i ;
    
    // รับข้อมูลนักเรียน 3 คน
    for ( i = 0 ; i < 3 ; i++ ) {
        printf( " Student %d :\n " , i + 1 ) ;
        printf( " Name : " ) ;
        scanf( " %[ ^\n ] " , students[ i ].Name ) ;
        printf( " ID: " ) ;
        scanf( " %s " , students[ i ].ID ) ;
        printf( " Scores in Subject 1 : " ) ;
        scanf( " %f ", &students[ i ].ScoreSub1 ) ;
        printf( " Scores in Subject 2 : " ) ;
        scanf( " %f " , &students[ i ].ScoreSub2 ) ;
        printf( " Scores in Subject 3 : ");
        scanf( " %f " , &students[ i ].ScoreSub3 ) ;
        printf( " Scores in Subject 4 : " ) ;
        scanf( " %f " , &students[ i ].ScoreSub4 ) ;
        printf( " Scores in Subject 5 : " ) ;
        scanf( " %f " , &students[ i ].ScoreSub5 ) ;
    }
    
    // แสดงผลลัพธ์
    for ( i = 0 ; i < 3 ; i++ ) {
        printf( " \nStudent %d :\n ", i + 1 ) ;
        printf( " Name: %s\n ", students[ i ].Name ) ;
        printf( " ID : %s\n ", students[ i ].ID ) ;
        printf( " Scores : %.1f %.1f %.1f %.1f %.1f\n " , students[ i ].ScoreSub1 , students[ i ].ScoreSub2 , students[ i ].ScoreSub3 , students[ i ].ScoreSub4 , students[ i ].ScoreSub5 ) ;

        // ตัดเกรดแต่ละวิชา
        printf( "Grades: " ) ;
        float scores[ 5 ] = {students[ i ].ScoreSub1 , students[ i ].ScoreSub2 , students[ i ].ScoreSub3 , students[ i ].ScoreSub4 , students[ i ].ScoreSub5 } ;
        for ( int j = 0 ; j < 5 ; j++ ) {
            if ( scores[ j ] >= 80 )
                printf( " A " ) ;
            else if ( scores[ j ] >= 75 )
                printf( " B+ " ) ;
            else if ( scores[ j ] >= 70 ) 
                printf( " B " ) ;
            else if ( scores[ j ] >= 65 ) 
                printf( " C+ " ) ;
            else if ( scores[ j ] >= 60 )
                printf( " C " ) ;
            else if ( scores[ j ] >= 55 )
                printf( " D+ " ) ;
            else if ( scores[ j ] >= 50 )
                printf( " D " ) ;
            else
                printf( " F " ) ;
        }
        printf( " \n " ) ;

        // คำนวณคะแนนเฉลี่ย
        float average = ( students[ i ].ScoreSub1 + students[ i ].ScoreSub2 + students[ i ].ScoreSub3 + students[ i ].ScoreSub4 + students[ i ].ScoreSub5 ) / 5 ;
        printf( " Average Scores: %.1f\n " , average ) ;
    }

    return 0 ;
}
