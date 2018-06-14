/*
* This file is part of the ghassani/miva-bintools package.
*
* (c) Gassan idriss <ghassani@gmaill.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

#include "hexdump.h"

void hexdump(unsigned char *data, unsigned int amount) {
    return hexdump(data, amount, stderr);
}

void hexdump(unsigned char *data, unsigned int amount, FILE* file) {
    unsigned int    dp, p;  /* data pointer */
    for (dp = 1; dp <= amount; dp++) {
        fprintf(file, "%02x ", data[dp - 1]);
        if ((dp % 8) == 0)
            fprintf(file, " ");
        if ((dp % 16) == 0) {
            fprintf(file, "| ");
            p = dp;
            for (dp -= 16; dp < p; dp++)
                fprintf(file, "%c", hex_trans_dump[data[dp]]);
            fflush(file);
            fprintf(file, "\n");
        }
        fflush(file);
    }
    // tail
    if ((amount % 16) != 0) {
        p = dp = 16 - (amount % 16);
        for (dp = p; dp > 0; dp--) {
            fprintf(file, "   ");
            if (((dp % 8) == 0) && (p != 8))
                fprintf(file, " ");
            fflush(file);
        }
        fprintf(file, " | ");
        for (dp = (amount - (16 - p)); dp < amount; dp++)
            fprintf(file, "%c", hex_trans_dump[data[dp]]);
        fflush(file);
    }
    fprintf(file, "\n");

    return;
}