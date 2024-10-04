#include <stdio.h>
#include <string.h>
#include <ctype.h>

int isPalindrome(char str[]) {
    int left = 0 ;
    int right = strlen(str) - 1 ;

  while (left < right) {
    while (left < right && !isalnum(str[left])) {
        left++;
    }//end while
    while (left < right && !isalnum(str[right])) {
        right--;
    }//end while         
    if (tolower(str[left]) != tolower(str[right])) {
        return 0 ;
    }//end if
    left++;
    right--;
  }//end whaile 
  return 1 ;  
}//end int

int main() {
    char word[100] ;

    printf ( "Enter word: " ) ;
    //fgets(word, sizeof(word), stdin);
    scanf ( "%s" , word ) ;

    if ( isPalindrome(word) ) {
        printf ( "Pass. \n " ) ;
    } else { 
        printf ( "Not Pass. \n " ) ; 
    }//end else
    return 0 ;
}//end main function