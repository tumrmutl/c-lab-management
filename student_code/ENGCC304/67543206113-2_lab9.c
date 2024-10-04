#include <stdio.h>
#include <stdbool.h>

bool check_prime( int cp ) ;

int main(){
    int n ;
    int array[n];
    
    //---Input arrey(n)---//
    printf( "Enter N: " ) ;
    scanf( "%d" , &n ) ;

    //---input num of array---//
    for ( int v = 0 ; v < n ; v++ ) {
        printf( "Enter value[%d]: " , v ) ;
        scanf( "%d" , &array[v] ) ;
    }//end for
    
    //----Start Output----//
    printf( "Output:  \n") ;
    printf( "    Index:  " ) ;
    for ( int i = 0 ; i < n ; i++ ) {
        printf( "%d  " , i ) ;
    }//end for
    printf( "\n" );
    
    printf( "    Array:  " ) ;
    for ( int a = 0 ; a < n ; a++ ) {
        if ( check_prime(array[a]) ) {
            printf( "%d  " , array[a] ) ;
        } else {
            printf( "#  " ) ;
        }//end if else
    }//end for
    printf( "\n" ) ;

    return 0 ;
}//end function

//----check_prime----//
bool check_prime( int cp ) {
    if ( cp < 2 )
        return false ;
    for ( int i = 2 ; i * i <= cp ; i++ ) {
        if ( cp % i == 0 )
            return false ;
    }//end for
    return true ;
}//end function