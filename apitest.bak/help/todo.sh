#!/bin/bash
TF_1=$(mktemp);
TF_2=$(mktemp);
grep -o -h "|[a-zA-Z_.]\+|" * -r | sort | uniq | sed 's/|//g' > $TF_1
sed 's/^\([^\t]\+\).*/\1/' tags | sort > $TF_2
diff $TF_2 $TF_1 | grep -F '> ' | sed 's/> \(.\+\)/|\1|/' > $TF_1
grep -F -o -f $TF_1 * -r
rm $TF_1 $TF_2
