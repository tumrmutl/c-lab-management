#include <stdio.h>
#include <math.h>

int isPrime(int num) {
    if (num < 2) return 0 ;
    for (int i = 2; i <= sqrt(num); i++) {
        if (num % i == 0) return 0;
    }//end if
    return 1;
}//end int

int main() {
    int n;
    printf("Enter N : ");
    scanf("%d", &n);

    int arr[n];
    for (int i = 0; i < n; i++) {
        printf("Enter value[%d] : ", i);
        scanf("%d", &arr[i]);
    }//end for

    printf("Index:  ");
    for (int i = 0; i < n; i++) {
        printf("%2d ", i);
    }//end for
    printf("\n");

    printf("Array:  ");
    for (int i = 0; i < n; i++) {
        if (isPrime(arr[i]))
            printf("%2d ", arr[i]);
        else
            printf("%2s ", "#");
    }//end for 
    printf("\n");

    return 0;
}//end funtion
