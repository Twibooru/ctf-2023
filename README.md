Twibooru CTF 2023 (AKA MareCTF)
===============================

The CTF consisted of 5 stages.

## Stage 1
A hint was posted on the Twibooru forums as well as on 4chan as to where to find the first stage:

```
Also: Does the term "CTF" mean anything to you? There might be one lurking over on this very domain waiting for you to find. No automated scanning of any kind is required. Hint: If you can pass the turing test, you know everything you need to start. But if not, you might be disallowed.
Does that mean nothing to you? It's probably not for you, then :)
```

In the robots.txt file on Twibooru.org, one would find the following:

```
# [IMPORTANT RULES/NOTES]
# - This CTF is linear. Every flag is supplied in the format "flag{f14g_h3r3} (n/5)" where n is the stage you have completed.
#   Once you have found the flag for stage n, you don't need to return to any stage before that in order to progress.
#   It is not intended to be possible to find a flag for stage n before finding the flag for stage n-1,
#   unless you get very lucky or somehow miss the flag but complete the stage anyways.
# - No scanning, bruteforcing, hash cracking, or automated exploitation of any kind is required -
#   the correct solution to each of these problems can be performed by a human in a reasonable amount of time.
#   Note, however, that writing programs to perform certain local computations on data you have obtained may be beneficial.
# - If you find an actual vulnerability in the challenges that does not appear to be part of the CTF, please report it to me (Floorb),
#   and please do not exploit it for any malicious purpose.
# - Please do not share flags or solutions publicly until after the challenge is complete (ie: the end of April 2, UTC time zone.)
# - There may be prize(s) - sharing flags will likely hinder any prize(s) you get.
# - If you want to track your flags, PM them to me (Floor Bored) on Twibooru
# - The levels are intended to increase in difficulty. Levels 1 and 2 are simple. Beyond that, the difficulty ramps up.
# - The following skills will be useful, in no particular order: steganography, basic web exploitation,
#   binary exploitation, basic programming, (extremely, hilariously) basic cryptography.
# - If you're experiencing any problems, please let me know on 4chan or Twibooru.
# - Have fun!
# flag{[redacted]}
Disallow: /m4r35/ # < that trailing slash is important
```

## Stage 2
Stage 2 was accessed by pointing a Web browser at the path revealed in the robots.txt file above. The source code of this challenge (as well as Stage 3) can be found in the `m4r35/` sub-directory of this repository.

It was a simple challenge, presenting a login page with a rather insecure password reset form, which required only the username and a single security question to reset the password. The username (`jimm`,) could be found in a comment in the source code of the login page (and all other pages, actually.) After that, it was a simple matter of guessing the answers to the security questions, which could be obtained either by means of prior knowledge, or by guesswork from the text on the page.

After resetting the password and logging in, the user would be redirected to Stage 3. The flag for stage 2 was in an HTML comment in the page that was available after login.

## Stage 3
This was a continuation of Stage 3. The user was presented with a simple database of pictures of mares. There were four of them, and one was different from all the others - it was explicitly marked as "suspicious," the description format was different from the other pictures, and the file was hosted locally rather than hotlinked from Twibooru.

### Initial version
The initial version of Stage 3 was a lot more subtle. It included the phrase "anyone's guess how I get get it out of her," and nothing in the EXIF comment. However, both people who tested the thing for me initially got stuck on this level for way too long. So I made it easier.

### Public version
The public version has more emphasis on "OUT" and "GUESS," and even included a comment in the EXIF comment field of the file putting yet more emphasis on this. This was a reference to the Cicada 3301 series of challenges, which used OutGuess as a steganography tool, and used another word play on the words "guess" and "out" in order to hint at it.

Upon running the image through `outguess -r`, the user was greeted with a message, containing another flag, and a link to a passworded PonePaste paste, along with the password.

## Stage 4
Upon opening the PonePaste paste, the user was greeted with a large block of text, consisting only of the tokens "mares" and "i love them", along with periods. One of the tokens mapped to a zero, the other to a one. The periods delimited 8-bit binary numbers. When decoded in this way and mapped using ASCII, another flag was revealed, along with a telnet host and port. A version of this paste (as well as a copy of the very bad code I used to make it) is included in this repo.

## Stage 5
The secret MareServer. The source code is included in this repo. When telnetting to the given host and port, a user would be presented with the following menu:

```
----- Welcome to the secret MareServer -----
Your options:
1) Download all mares
2) Upload new mare
3) Delete all mares
4) Access MareNotes(tm)
Selection: 
```

The first three options were filler, and had no relevance to the challenge. The 4th option was where the challenge lay.

```
Selection: 4
Please enter passmare ("public" = public access, your mareword for secret access): 
```

Entering "public" would yield the following:

```
----- [Contents of public-marenotes.txt] -----
[snip contents]
-----------
```

An invalid password yielded the following:
```
Please enter passmare ("public" = public access, your mareword for secret access): password
error: invalid password!
```

The contents of the `public-marenotes.txt` file are irrelevant, and hold no meaning other than filler text. If one were to spend some time experimenting with this prompt and trying different things, they would eventually find that certain inputs caused the server to abruptly close their connection, rather than dropping them back to the main menu as a valid input would.

This behavior was due to overflowing the stack. The structure of the relevant function is as follows (some parts omitted, see the full version in the source):

```c
void access_marenotes() {
    char marenotes[21] = { 0 };
    char pass[16];

    // [...]
    strcpy(marenotes, "public-marenotes.txt");

    // [...]
    scanf(" %s", pass);

    // this password was never intended to be discovered, but if nobody solved the challenge, I would have released the binary with this code in place.
    if (!strcmp(pass, "[redacted]")) { 
        strcpy(marenotes, "secret-marenotes.txt");
    } else if (strncmp(pass, "public", 6) != 0) {
        printf("error: invalid password!\n");
        return;
    }

    // [...]
    fp = fopen(marenotes, "r");
    // [...]
    // output the file...
}
```

So, one could overflow the `pass` buffer into the `marenotes` buffer (which contains the filename) by inputting `public`, followed by 10 characters of anything, followed by the filename they wish to read - and ta-da, you can read an arbitrary file.

The password prompt hints that the correct filename to read would be `secret-marenotes.txt`, and, upon reading such file, the user was presented with a final flag and a congratulations.

## Random Notes
### PHP
Some of you might realize that Twibooru isn't powered by PHP. It would also be rather silly to host intentionally-exploitable code on the same server as our production site. So... there was an entirely separate virtual machine running the PHP code for the CTF, and I configured Twibooru's haproxy to proxy /m4r35/ to that server. I am bad at haproxy configuration, which is why the note "that trailing slash is important." 

### telnetd
The telnet server was just FreeBSD's inetd executing telnetd with my binary as the login program. I was originally running the telnetd on port 23, but automated exploit scripts quickly overwhelmed my little VPS and kept hanging inetd. I don't know how to change the port properly (inetd.conf doesn't like it when I just specify the port number,) so I just edited /etc/services to add:

```
not_telnet 2223/tcp
```

and then edited /etc/inetd.conf to add:
```
not_telnet stream tcp nowait root /usr/libexec/telnetd telnetd -a off -p /root/mareserver
```

Someone actually discovered you can sent CTRL-T (which is SIGINFO) and get it to spit out the following. This was not part of the challenge:

```
load: 0.46  cmd: mareserver 47077 [ttyin] 628.14r 0.00u 0.00s 0% 1784k
mi_switch+0xc2 sleepq_catch_signals+0x2e6 sleepq_wait_sig+0x9 _cv_wait_sig+0xec tty_wait+0x1c ttydisc_read+0x2cc ttydev_read+0x56 devfs_read_f+0xd5 dofileread+0x81 sys_read+0xbc amd64_syscall+0x10c fast_syscall_common+0xf8 
```

One last note: I saw at least 2 people who were confused when they connected to the telnetd with `nc` and just got unprintable garbage. Telnet is a real protocol! It's not just a synonym for "a raw connection" and the telnet client is not equivalent to "netcat" even though some people seem to think it is. The garbage people were seeing printed was part of the telnet handshake.

## Jimm
Jimm is a real person, a good friend of mine who helped me a little bit with putting this thing together, and a lot with testing it before it was released. He's been quite entertained by some of the characterizations people have given him while solving this CTF (and so have I!)