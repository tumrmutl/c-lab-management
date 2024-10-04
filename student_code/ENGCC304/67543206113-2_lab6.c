#include <stdio.h>
int main(){
int number; printf("Enter Number: "); scanf("%d", &number);
    for (int i = 0; i < number; i++) {
        for (int j = 0; j < number; j++) {
            if (number % 2 == 0) {
                if(i == j){
                    printf("1");
                } else {
                    printf("0");
                }
            }
            if (number % 2 == 1) {
                if(j == number - i - 1){
                    printf("1");
                } else {
                    printf("0");
                }
            }
        }
        printf("\n");
    }
    return 0;
}