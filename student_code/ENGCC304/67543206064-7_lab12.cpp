#include <stdio.h>

struct Student {
    char Name[ 20 ] ;
    char ID[ 5 ] ;
    float ScoreSub1 ;
    float ScoreSub2 ;
    float ScoreSub3 ;
    float ScoreSub4 ;
    float ScoreSub5 ;
} typedef S ;                            // เพื่อสร้างตัวย่อ S ให้ struct Student
    
const char* getGrade( float Score ) ;    // Prototype

int main() {
    
    S stu[ 3 ] ;                         // ใช้ตัวย่อ S แทน struct Student
    
    for( int i = 0 ; i < 3 ; i++ ) {
        printf( "Enter the details of 3 students :\n" ) ;
        printf( "Name :\n" ) ;
        scanf( "%s", stu[ i ].Name ) ;
        
        printf( "ID :\n" ) ;
        scanf( "%s", stu[ i ].ID ) ;

        printf( "Scores in subject 1 : " ) ;
        scanf( "%f", &stu[ i ].ScoreSub1 ) ;
        printf( "Scores in subject 2 : " ) ;
        scanf( "%f", &stu[ i ].ScoreSub2 ) ;
        printf( "Scores in subject 3 : " ) ;
        scanf( "%f", &stu[ i ].ScoreSub3 ) ;
        printf( "Scores in subject 4 : " ) ;
        scanf( "%f", &stu[ i ].ScoreSub4 ) ;
        printf( "Scores in subject 5 : " ) ;
        scanf( "%f", &stu[ i ].ScoreSub5 ) ;
        
    }//end for
    
    printf( "Student Details : " ) ;
    for( int i = 0 ; i < 3 ; i++ ) {
        printf( "\nStudent %d :\n", i + 1 ) ;
        printf( "Name : %s\n", stu[ i ].Name ) ;
        printf( "ID : %s\n", stu[ i ].ID ) ;
        printf( "Score : %.1f %.1f %.1f %.1f %.1f\n", 
            stu[ i ].ScoreSub1,
            stu[ i ].ScoreSub2,
            stu[ i ].ScoreSub3,
            stu[ i ].ScoreSub4,
            stu[ i ].ScoreSub5 ) ;
        printf( "Grade : %s %s %s %s %s\n",
            getGrade( stu[ i ].ScoreSub1 ),
            getGrade( stu[ i ].ScoreSub2 ),
            getGrade( stu[ i ].ScoreSub3 ),
            getGrade( stu[ i ].ScoreSub4 ),
            getGrade( stu[ i ].ScoreSub5 ) ) ;
        
    }//end for
    
    return 0 ;
    
}//end function main

const char* getGrade( float Score ) {     // เพิ่ม const ให้กับ char* ในฟังก์ชัน getGrade เพื่อระบุว่าข้อความที่คืนค่ากลับเป็นค่าคงที่ (string literals) ซึ่งไม่สามารถแก้ไขได้
    if( Score >= 80 ) {
        return "A" ;
    } else if( Score >= 75 ) {
        return "B+" ;
    } else if( Score >= 70 ) {
        return "B" ;
    } else if( Score >= 65 ) {
        return "C+" ;
    } else if( Score >= 60 ) {
        return "C" ;
    } else if( Score >= 55 ) {
        return "D+" ;
    } else if( Score >= 50 ) {
        return "D" ;
    } else {
        return "F" ;
    }//end if
    
}//end function getGrade
