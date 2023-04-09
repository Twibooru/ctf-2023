#include <ctype.h>
#include <errno.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>

void print_menu() {
    printf("----- Welcome to the secret MareServer -----\n");
    printf("Your options:\n");
    printf("1) Download all mares\n");
    printf("2) Upload new mare\n");
    printf("3) Delete all mares\n");
    printf("4) Access MareNotes(tm)\n");
    printf("Selection: ");
}

void download_all_mares() {
    printf("Initializing download engine.....\n");
    sleep(1);
    printf("Counting mares for download...\n");
    sleep(1);
    printf("Error: errno ETOOMANYMARES\n");
}

void upload_new_mare() {
    printf("Error: out of disk space - please try again later.\n");
}

void delete_all_mares() {
    printf("Why... why would you ever do such a thing? My mares...\n"
        " I love them, I must keep them safe, why don't you love them too?!\n"
        " How could you even think about hurting these kind, innocent, beautiful, huggable, scritchable, lovable MARES?!\n");
}

void access_marenotes() {
    char marenotes[21] = { 0 };
    char pass[16];
    FILE *fp;
    int c;

    strcpy(marenotes, "public-marenotes.txt");

    printf("Please enter passmare (\"public\" = public access, your mareword for secret access): ");
    scanf(" %s", pass);

    // this password was never intended to be discovered, but if nobody solved the challenge, I would have released the binary with this code in place.
    if (!strcmp(pass, "[redacted]")) { 
        strcpy(marenotes, "secret-marenotes.txt");
    } else if (strncmp(pass, "public", 6) != 0) {
        printf("error: invalid password!\n");
        return;
    }

    // very simple code to prevent path traversal and reading of truly arbitrary files.
    for (int i = 0; i < 21; i++) {
        if (marenotes[i] == '/' || marenotes[i] == '\\') {
            marenotes[i] = ' ';
        }
    }

    fp = fopen(marenotes, "r");

    if (!fp) {
        printf("error: failed to open %s: %s\n", marenotes, strerror(errno));
        return;
    }

    printf("----- [Contents of %s] -----\n", marenotes);
    while ((c = fgetc(fp)) != EOF) {
        putchar(c);
    }
    printf("-----------\n");

    fclose(fp);
}

int main(int argc, char *argv[]) {
    int authorized = 0;
    char choice;
    int c;

    chdir("/usr/local/share/mareserver/");

    while (!feof(stdin)) {
        print_menu();
        scanf(" %c", &choice);
        while ( (c = getchar()) != '\n' && c != EOF ) { }

        if (c == EOF) {
            break;
        }

        switch (choice) {
        case '1':
            download_all_mares();
            break;
        case '2':
            upload_new_mare();
            break;
        case '3':
            delete_all_mares();
            break;
        case '4':
            access_marenotes();
            break;
        default:
            printf("Invalid choice, please try again!\n");
            break;
        }
    }

    return 0;
}
