#include <stdio.h>

typedef struct {
    char n[ 20 ] ; 
    char id[ 5 ] ; 
    float s1 , s2 , s3 , s4 , s5 ; 
} S ;


void getGrades( float s[] , char gr[][ 3 ] ) {
    for ( int i = 0 ; i < 5; i++ ) {
        if ( s[ i ] >= 80 ) sprintf(gr[ i ], "A");
        else if ( s[i] >= 75 ) sprintf( gr[ i ] , "B+" );
        else if ( s[i] >= 70 ) sprintf( gr[ i ] , "B" );
        else if ( s[i] >= 65 ) sprintf( gr[ i ] , "C+" );
        else if ( s[i] >= 60 ) sprintf( gr[ i ] , "C" );
        else if ( s[i] >= 55 ) sprintf( gr[ i ] , "D+" );
        else if ( s[i] >= 50 ) sprintf( gr[ i ] , "D" );
        else sprintf( gr[ i ] , "F" ) ;
    }//end for
}//end function


void printStudentGrades( S st ) {
    float sc[ 5 ] = { st.s1 , st.s2 , st.s3 , st.s4 , st.s5 } ;
    char gr[ 5 ][ 3 ];  
    float avg = 0 ;

    getGrades( sc, gr ) ;
    
    for ( int i = 0 ; i < 5 ; i++ ) {
        avg += sc[ i ] ;
    }
    avg /= 5 ;

    printf( "Name: %s\n", st.n ) ;
    printf( "ID: %s\n", st.id ) ;
    printf( "Scores: %.0f %.0f %.0f %.0f %.0f\n" , sc[ 0 ] , sc[ 1 ] , sc[ 2 ] , sc[ 3 ] , sc[ 4 ] ) ;
    
    printf( "Grades: " ) ;
    for ( int i = 0 ; i < 5; i++ ) {
        if ( i > 0 ) printf( " " ) ;
        printf( "%s" , gr[i] ) ;
    }//end for
    printf( "\n" ) ;

    printf( "Average Scores: %.1f\n\n" , avg ) ;
}

int main() {
    S st[ 3 ] ;

    for ( int i = 0 ; i < 3 ; i++ ) {
        printf( "Student %d:\n" , i + 1 ) ;
        printf( "Name: " ) ;
        scanf( " %[^\n]s" , st[i].n ) ;
        printf( "ID: " ) ;
        scanf( "%s" , st[i].id ) ;
        printf( "Scores in Subject 1: " ) ;
        scanf( "%f" , &st[i].s1 ) ;
        printf( "Scores in Subject 2: " ) ;
        scanf( "%f" , &st[i].s2 ) ;
        printf( "Scores in Subject 3: " ) ;
        scanf( "%f" , &st[i].s3 ) ;
        printf( "Scores in Subject 4: " ) ;
        scanf( "%f" , &st[i].s4 ) ;
        printf( "Scores in Subject 5: " ) ;
        scanf( "%f" , &st[i].s5 ) ;
        printf( "\n" ) ;
    }//end for

    for ( int i = 0 ; i < 3; i++ ) {
        printf( "Student %d:\n" , i + 1 ) ;
        printStudentGrades( st[i] ) ;
    }//end for

    return 0;
}//end function