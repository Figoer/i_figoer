#!/bin/bash
> tags
for i in `find $@ -maxdepth 1 -type f ! -name tags ! -name ".*"`; do
    grep -o "\*[a-zA-Z_.]\+\*" $i | awk '{gsub("*","");print $1"\t""'$i'""\t/*"$1"*"}' >> tags
done;

TMP_LC_CTYPE=$LC_CTYPE
LC_CTYPE=en_US.ascii
export LC_CTYPE
sort -o tags tags
LC_CTYPE=$TMP_LC_CTYPE
export LC_CTYPE
