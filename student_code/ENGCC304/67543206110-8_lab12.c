#include <stdio.h>
#include <string.h>

typedef struct {
  char Name[20];
  char ID[5];
  float Scores[5];
} Student ;

char *getGrade(float score) {
  if (score >= 80) return "A" ;
  else if (score >= 75) return "B+" ;
  else if (score >= 70) return "B" ;
  else if (score >= 65) return "C+" ;
  else if (score >= 60) return "C" ;
  else if (score >= 50) return "D+" ;
  else if (score >= 45) return "D" ;
  else return "F" ;
} // ent char

void printStudentDetails(Student student) {
  float average = 0;
  printf("Name: %s\n", student.Name);
  printf("ID: %s\n", student.ID);

  printf("Scores: ");
  for (int i = 0; i < 5; i++) {
    printf("%.0f ", student.Scores[i]);
    average += student.Scores[i];
  }//end for

  printf("\nGrades: ");
  for (int i = 0; i < 5; i++) {
    printf("%s ", getGrade(student.Scores[i]));
  }//end for

  average /= 5;
  printf("\nAverage Scores: %.1f\n\n", average);
}//end void

int main() {
  Student Students[3];

  for (int i = 0; i < 3; i++) {
    printf("Student %d:\nName: ", i + 1) ;
    scanf(" %[^\n]%*c", Students[i].Name) ;
    printf("ID: ") ;
    scanf("%s", Students[i].ID) ;
    for (int j = 0; j < 5; j++) {
      printf("Scores in Subject %d: ", j + 1) ;
      scanf("%f", &Students[i].Scores[j]) ;
    }//end for
    printf("\n") ;
  }//end for

  printf("Student Details:\n") ;
  for (int i = 0; i < 3; i++) {
    printf("Student %d:\n", i + 1) ;
    printStudentDetails(Students[i]) ;
  }//end for

  return 0;
}//end funtion