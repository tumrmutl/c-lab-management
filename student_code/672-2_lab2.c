#include <stdio.h>
int main() {

    int N ;
    printf( "enter line : " ) ;
    scanf( "%d", &N ) ;
    for( int i = 0 ; i < N ; i++ ) {
        printf( "[%d] hello world\n", i ) ;
    }

    return 0;
}
