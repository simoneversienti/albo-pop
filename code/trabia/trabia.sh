#!/bin/bash


# imposto la cartella di output esposta sul web
cartella="/var/www/albopop/trabia"

# imposto una cartella di lavoro
output="/var/albopop/trabia"

#### Download, clean e mere dei dati ####

curl 'http://www.comunetrabia.gov.it/index.php?p=consulta_albo_pretorio_tutti' -s | /usr/local/bin/scrape -be '//table//tr[contains(@style, "font-size:12px;")]' | xml2json | jq '[.html.body.tr[] | {titolo:.td[0].b[3].b[0],data:.td[0].b[3].b[1]["#text"],link:.td[1].a."@href"}]' | in2csv -f json  > $cartella/trabia.csv


curl 'http://www.comunetrabia.gov.it/index.php?p=consulta_albo_pretorio_tutti' -s | /usr/local/bin/scrape -be '//table//tr[contains(@style, "font-size:12px;")]' | xml2json | jq '[.html.body.tr[] | {titolo:.td[0].b[3].b[0],data:.td[0].b[3].b[1]["#text"],link:.td[1].a."@href"}]' | sed 's/\\r\\n//' | sed 's/\\r\\n//' | in2csv -f json  | csvformat -T | csvformat -D "|" -t | sed 's/&/&amp;/' | sed 's/Â//' | sed 's/â€//g' | sed 's/Ã//g' > $output/input.csv

curl 'http://www.comunetrabia.gov.it/index.php?p=consulta_albo_pretorio_tutti' -s | /usr/local/bin/scrape -be '//table//tr[contains(@style, "font-size:12px;")]' | xml2json | jq '[.html.body.tr[] | {titolo:.td[0].b[3].b[0],data:.td[0].b[3].b[1]["#text"],link:.td[1].a."@href"}]' | sed 's/\\r\\n//' | sed 's/\\r\\n//'  > $output/trabia.json

curl 'http://www.comunetrabia.gov.it/index.php?p=consulta_albo_pretorio_tutti' -s | /usr/local/bin/scrape -be '//table//tr[contains(@style, "font-size:12px;")]' | xml2json | jq . > $output/trabia_raw.json


# variabili per la costruzione del feed RSS
nomeFeed="Albo pretorio comune di Trabia"
descrizioneFeed="Il feed RSS dell'Albo pretorio comune di Trabia"
PageSource="http://blog.spaziogis.it/static/ods/data/albopop/trabia/feed.xml"

intestazioneRSS="<rss version=\"2.0\"><channel><title>$nomeFeed</title><description>$descrizioneFeed</description><link>$PageSource</link>"

chiusuraRSS="</channel></rss>"

preURL="http://www.comunetrabia.gov.it/"
# variabili per la costruzione del feed RSS

#cancello file, in modo che l'output del feed sia riempito sempre a partire da file "vuoti"
rm $output/out.xml
rm $output/feed.xml

#rimuovo intestazione dal file csv
sed -e '1d' $output/input.csv > $output/input_nohead.csv

# ciclo per ogni riga del csv per creare il corpo del file RSS
INPUT="$output/input_nohead.csv"
OLDIFS=$IFS
IFS="|"
[ ! -f $INPUT ] && { echo "$INPUT file not found"; exit 99; }
while read titolo data link
do

# riformatto la data in formato compatibile RSS, ovvero RFC 822, altrimenti il feed RSS non passa la validazione
OLD_IFS="$IFS"
IFS="-"
STR_ARRAY=( $data )
IFS="$OLD_IFS"
anno=${STR_ARRAY[2]}
mese=${STR_ARRAY[1]}
giorno=${STR_ARRAY[0]}
dataok=$(LANG=en_EN date -Rd "$anno-$mese-$giorno")

# creo il corpo del feed RSS
echo "<item><title>$titolo</title><link>$preURL$link</link><pubDate>$dataok</pubDate></item>" >> $output/out.xml

done < $INPUT
IFS=$OLDIFS

# creo il feed RSS, facendo il merge di intestazione, corpo e piede
echo $intestazioneRSS >> $output/feed.xml
cat $output/out.xml >> $output/feed.xml
echo $chiusuraRSS >> $output/feed.xml

cat $output/feed.xml > $cartella/feed.xml



