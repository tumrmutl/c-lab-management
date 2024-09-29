#include <stdio.h>
#include <stdlib.h>

void send_line_notify(const char *token, const char *message) {
    // สร้างคำสั่งที่ต้องการส่งไปยัง Line Notify
    char command[512];
    snprintf(command, sizeof(command),
             "curl -X POST https://notify-api.line.me/api/notify "
             "-H 'Authorization: Bearer %s' "
             "-F 'message=%s'", token, message);

    // ใช้ system() เพื่อเรียกคำสั่ง curl
    system(command);
}

int main(void) {
    // Line Notify Token ที่ได้รับจากการสมัคร
    const char *token = "W3TjiAFitVc6sJsMOYC11HIwo9WZGNggCvwZvOn7Ixj";  // แทนที่ด้วย token จริงของคุณ

    // ข้อความที่ต้องการส่ง
    const char *message = "Hello from C without libcurl!";

    // เรียกฟังก์ชันเพื่อส่งข้อมูล
    send_line_notify(token, message);

    return 0;
}
