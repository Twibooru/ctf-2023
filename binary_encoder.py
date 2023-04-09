#!/usr/bin/env python3
import codecs

DICTIONARY = [
    'i love them',
    'mares'
]


def encode_char(c):
    output = ''
    bits = bin(ord(c))[2:]

    for bit in bits:
        output += DICTIONARY[int(bit)] + ' '

    return output.strip()


def encode_string(s):
    output = ''
    for c in s:
        output += encode_char(c) + '. '

    return output


def full_encode(sentence):
    return encode_string(codecs.encode(sentence, 'rot13'))

encoded = full_encode("flag{[snip]} (4/5)\n" +
    "don't tell anyone but the secret mare server is at x.x.x.x:2223 (telnet)")

print(encoded)
