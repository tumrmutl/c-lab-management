#include <stdio.h>

// โครงสร้างข้อมูลสำหรับนักเรียน
struct Student {
    char Name[20];
    char ID[5];
    float ScoreSub1;
    float ScoreSub2;
    float ScoreSub3;
    float ScoreSub4;
    float ScoreSub5;
} typedef S;

// ฟังก์ชันสำหรับคำนวณเกรดพร้อมเครื่องหมาย +
const char* getFullGrade(float score) {
    if (score >= 80) return "A";
    else if (score >= 75) return "B+";
    else if (score >= 70) return "B";
    else if (score >= 65) return "C+";
    else if (score >= 60) return "C";
    else if (score >= 55) return "D+";
    else if (score >= 50) return "D";
    else return "F";
}

int main() {
    S students[3];  // เก็บข้อมูลนักเรียน 3 คน

    // รับข้อมูลนักเรียน
    for (int i = 0; i < 3; i++) {
        printf("Enter the details of Student %d:\n", i + 1);
        printf("Name: ");
        scanf(" %[^\n]%*c", students[i].Name);  // อ่านชื่อ
        printf("ID: ");
        scanf("%s", students[i].ID);  // อ่านรหัสนักศึกษา
        printf("Scores in Subject 1: ");
        scanf("%f", &students[i].ScoreSub1);
        printf("Scores in Subject 2: ");
        scanf("%f", &students[i].ScoreSub2);
        printf("Scores in Subject 3: ");
        scanf("%f", &students[i].ScoreSub3);
        printf("Scores in Subject 4: ");
        scanf("%f", &students[i].ScoreSub4);
        printf("Scores in Subject 5: ");
        scanf("%f", &students[i].ScoreSub5);
    }

    // แสดงข้อมูลนักเรียน
    for (int i = 0; i < 3; i++) {
        float totalScore = 0.0;
        printf("\nStudent %d:\n", i + 1);
        printf("Name: %s\n", students[i].Name);
        printf("ID: %s\n", students[i].ID);
        printf("Scores: %.0f %.0f %.0f %.0f %.0f\n", students[i].ScoreSub1, students[i].ScoreSub2, students[i].ScoreSub3, students[i].ScoreSub4, students[i].ScoreSub5);
        
        // แสดงเกรดของนักเรียน
        printf("Grades: %s %s %s %s %s\n", getFullGrade(students[i].ScoreSub1), getFullGrade(students[i].ScoreSub2), getFullGrade(students[i].ScoreSub3), getFullGrade(students[i].ScoreSub4), getFullGrade(students[i].ScoreSub5));
        
        // คำนวณคะแนนเฉลี่ย
        totalScore = (students[i].ScoreSub1 + students[i].ScoreSub2 + students[i].ScoreSub3 + students[i].ScoreSub4 + students[i].ScoreSub5) / 5;
        printf("Average Scores: %.1f\n", totalScore);
    }

    return 0;
}
