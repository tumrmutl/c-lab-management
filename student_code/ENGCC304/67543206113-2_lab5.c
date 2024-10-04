#include<stdio.h>
#include <conio.h>
int main()
{
   int score;
   int result;
   
   printf("Enter score : ");
   result = scanf("%d", &score);
   
   if (result != 1) {
        
        printf("\n please enter number only.\n");
        return 1; 
    }
    
   switch (score)
    {
    case 80 ... 100:
        printf("A\n");
        break;
    case 75 ... 79:
        printf("B+\n");
        break;
    case 70 ... 74:
        printf("B\n");
        break;
    case 65 ... 69:
        printf("C+\n");
        break;
    case 60 ... 64:
        printf("C\n");
        break;
    case 55 ... 59:
        printf("D+\n");
        break;
    case 50 ... 54:
        printf("D\n");
        break;
    case 0 ... 49:
        printf("F\n");
        break;        
    default:
        printf("Invalid Score");
        break;
    }
    getch();
}