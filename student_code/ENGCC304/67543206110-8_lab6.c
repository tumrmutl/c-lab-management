#include <stdio.h>

int main() {
    int ip ;
    
    printf("Enter value: " ) ;
    scanf("%d" , &ip ) ;
    
    printf("Series: " ) ;
    
    if (ip % 2 != 0 ) {  
        for (int i = 1 ; i <= ip ; i += 2 ) {
            printf("%d " , i) ; 
        }
    } else {
        for (int i = ip ; i >= 0 ; i -= 2 ) {
            printf("%d " , i ) ; 
        }
    }
    
    printf("\n") ;
    
    return 0 ;
}
