#include <stdio.h>

int main() {
    
    int matrix4x4[4][4] = {
        {1, 0, 0, 0},
        {0, 1, 0, 0},
        {0, 0, 1, 0},
        {0, 0, 0, 1}
    };

    
    int matrix5x5[5][5] = {
        {0, 0, 0, 0, 1},
        {0, 0, 0, 1, 0},
        {0, 0, 1, 0, 0},
        {0, 1, 0, 0, 0},
        {1, 0, 0, 0, 0}
    };

    printf( "4x4 Identity Matrix:\n" ) ;
    for ( int i = 0; i < 4; i++ ) {
        for ( int j = 0; j < 4; j++ ) {
            printf( "%d ", matrix4x4[i][j] ) ;
        }
        printf( "\n" ) ;
    }

    printf( "\n" ) ; 

    
    printf( "5x5 Matrix:\n" ) ;
    for ( int i = 0; i < 5; i++ ) {
        for ( int j = 0; j < 5; j++ ) {
            printf( "%d ", matrix5x5[i][j] ) ;
        }
        printf( "\n" );
    }

    return 0;
}
