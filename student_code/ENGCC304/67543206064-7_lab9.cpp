#include <stdio.h>

int main() {
        
    int input_num = 0 ;

    printf( "Enter N : " ) ;
    scanf( "%d", &input_num ) ;
    
    int array[ input_num ] ;  //เก็บค่าอาร์เรย์ที่ผู้ใช้ป้อน
    
    for ( int i = 0 ; i < input_num ; i++ ) {
        printf( "Enter value [ %d ] : ", i ) ;
        scanf( "%d", &array[ i ] ) ;
    }//end for
    
    printf( "Index : " ) ;
    for ( int i = 0 ; i < input_num ; i++ ) {
        printf( "%d ",  i ) ;
    }//end for
    
    printf( "\nArray : " ) ;
    for ( int i = 0 ; i < input_num ; i++ ) {
        int prime_num = 1 ;   // สมมติว่าเป็นจำนวนเฉพาะ
        
        if ( array[ i ] < 2 ) {
            prime_num = 0 ;  // ถ้าค่าน้อยกว่า 2 ไม่เป็นจำนวนเฉพาะ
        } else {
            for ( int j = 2 ; j * j <= array[ i ] ; j++ ) {  //เริ่มจาก 2 และเพิ่มค่า j ไปเรื่อยๆ จนกว่าจนกว่า j * j จะมีค่ามากกว่าตัวเลขที่กำลังตรวจสอบ (array[i])
                if ( array[ i ] % j == 0 ) {
                    prime_num = 0 ;
                    break ;
                }//end if
            }//end for
        }//end if
        
        if ( prime_num ) {
            printf( "%d ", array [ i ] ) ;  // แสดงจำนวนเฉพาะ
        } else {
            printf( "# " ) ;  // แสดงเครื่องหมาย # สำหรับที่ไม่ใช่จำนวนเฉพาะ
        }//end if
        
    }//end for

    return 0 ;
    
}//end function
