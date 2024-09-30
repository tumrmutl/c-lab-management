#include <stdio.h>
#include <stdlib.h>
#include <time.h>

#define TEST_MODE // เปิดโหมดทดสอบ

int getRandomNumber() {
#ifdef TEST_MODE
    return 42; // สำหรับโหมดทดสอบให้คืนค่าเป็น 42
#else
    return rand() % 100 + 1; // สำหรับโหมดปกติให้ใช้การสุ่ม
#endif
}

void playGame() {
    int score = 100;
    int guess, randomNumber;
    int lowerBound = 1, upperBound = 100; // ขอบเขตเริ่มต้นของการทายเลข

    // สุ่มตัวเลขหรือคืนค่าคงที่ในโหมดทดสอบ
    randomNumber = getRandomNumber();

    printf("(Score=%d)\n", score);
    do {
        printf("Guess the winning number (%d-%d): ", lowerBound, upperBound);
        scanf("%d", &guess);

        if (guess >= lowerBound && guess <= upperBound) {
            if (guess != randomNumber) {
                score -= 10;

                if (guess < randomNumber) {
                    printf("Sorry, the winning number is HIGHER than %d. (Score=%d)\n", guess, score);
                    if (guess + 1 > lowerBound) {
                        lowerBound = guess + 1; // ปรับขอบเขตด้านล่างให้แคบลง
                    }
                } else {
                    printf("Sorry, the winning number is LOWER than %d. (Score=%d)\n", guess, score);
                    if (guess - 1 < upperBound) {
                        upperBound = guess - 1; // ปรับขอบเขตด้านบนให้แคบลง
                    }
                }
            }
        } else {
            printf("Your guess is out of the current bounds (%d-%d)! Try again.\n", lowerBound, upperBound);
        }

    } while (guess != randomNumber && score > 0);

    if (guess == randomNumber) {
        printf("That is correct! The winning number is %d.\n", randomNumber);
        printf("Score this game: %d\n", score);
    } else {
        printf("Game over! You've run out of points.\n");
    }
}

int main() {
    int choice;

    do {
        printf("Do you want to play game (1=play, -1=exit) : ");
        scanf("%d", &choice);

        if (choice == 1) {
            playGame();
        }

    } while (choice != -1);

    return 0;
}
