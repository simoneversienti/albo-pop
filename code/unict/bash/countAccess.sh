#!/bin/sh
# Count all the distinct access for a day from a access log file passed as standard output
# countAccess.sh <albo> <year> <month> <day>
curl -s http://dev.opendatasicilia.it/albopop/$1/access.log | \
grep -P "^$2\t$3\t$4" | \
cut -f 7 | \
sort | \
uniq | \
wc -l
