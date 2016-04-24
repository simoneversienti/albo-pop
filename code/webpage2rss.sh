#!/bin/bash

#### Obiettivi dello script ####

# Trasformare le pagine dell'albo pretorio di Pedata in un feed RSS.
#
# L'indirizzo dell'albo di Pedara è http://pubblicazioni.saga.it/publishing/AP/index.do?org=pedara

#### Requisiti ####

# - scrape, Extract HTML elements using an XPath query or CSS3 selector
# https://github.com/jeroenjanssens/data-science-at-the-command-line/blob/master/tools/scrape
#
# - xml2json, Convert XML to JSON Xml2Json
# https://github.com/parmentf/xml2json
#
# - jq, Process JSON
# https://stedolan.github.io/jq/
#
# csvkit, csvkit is a suite of utilities for converting to and working with CSV
# http://csvkit.readthedocs.org/en/0.9.1/


# imposto una cartella di lavoro
cartella="/var/albopop/pedara"

# imposto la cartella di output esposta sul web
output="/var/www/albopop/pedara"

#### Download, clean e mere dei dati ####

# imposto i cookie di sessione e scarico in csv le ultime 3 pagine
curl -s -c $cartella/cookies.txt  "http://pubblicazioni.saga.it/publishing/AP/index.do?org=pedara"  > /dev/null 2>&1

# tramite scrape, con CSS selector estraggo le righe della tabella dell'albo,  poi le trasformo in json con xml2json, poi faccio un po' di pulizia al json con jq e infine trasformo tutto in csv con in2csv
curl -s -b $cartella/cookies.txt  -L 'http://pubblicazioni.saga.it/publishing/AP/index.do?org=pedara' -s | /usr/local/bin/nadir/scrape -be '#documentList > tbody > tr:not(:first-child)' | tr '\n' ' ' |xml2json | jq '[.html.body.tr[] | {pubDate:.td[1]."#text",title:.td[4]."#text",id:.td[7].a."@href"}]' | in2csv -f json  > $cartella/01.csv
curl -s -b $cartell/cookies.txt  -L 'http://pubblicazioni.saga.it/publishing/AP/index.do?dispatch=search&d-5113963-p=2&org=pedara' -s | /usr/local/bin/nadir/scrape -be '#documentList > tbody > tr:not(:first-child)' | tr '\n' ' ' | xml2json | jq '[.html.body.tr[] | {pubDate:.td[1]."#text",title:.td[4]."#text",id:.td[7].a."@href"}]' | in2csv -f json  > $cartella/02.csv
curl -s -b $cartell/cookies.txt  -L 'http://pubblicazioni.saga.it/publishing/AP/index.do?dispatch=search&d-5113963-p=3&org=pedara' -s | /usr/local/bin/nadir/scrape -be '#documentList > tbody > tr:not(:first-child)' | tr '\n' ' ' | xml2json | jq '[.html.body.tr[] | {pubDate:.td[1]."#text",title:.td[4]."#text",id:.td[7].a."@href"}]' | in2csv -f json  > $cartella/03.csv

# faccio il merge dei 3 csv e imposto come separetore di campi il pipe ("|")
csvstack $cartella/01.csv $cartella/02.csv $cartella/03.csv | csvformat -D "|"  > $cartella/input.csv

#### Costruisco il feed RSS ####

# variabili per la costruzione del feed RSS
nomeFeed="Albo Pretorio Pedara"
descrizioneFeed="Il feed RSS dell'Albo Pretorio Pedara"
PageSource="http://blog.spaziogis.it/static/ods/data/albopop/pedara/feed_rss.xml"

intestazioneRSS="<rss version=\"2.0\"><channel><title>$nomeFeed</title><description>$descrizioneFeed</description><link>$PageSource</link>"

chiusuraRSS="</channel></rss>"
# variabili per la costruzione del feed RSS

#cancello file, in modo che l'output del feed sia riempito sempre a partire da file "vuoti"
rm $output/out.xml
rm $output/feed.xml

#rimuovo intestazione dal file csv
sed -e '1d' $cartella/input.csv > $cartella/input_nohead.csv

# ciclo per ogni riga del csv per creare il corpo del file RSS
INPUT="$cartella/input_nohead.csv"
OLDIFS=$IFS
IFS="|"
[ ! -f $INPUT ] && { echo "$INPUT file not found"; exit 99; }
while read data text href
do

# riformatto la data in formato compatibile RSS, ovvero RFC 822, altrimenti il feed RSS non passa la validazione
OLD_IFS="$IFS"
IFS="/"
STR_ARRAY=( $data )
IFS="$OLD_IFS"
anno=${STR_ARRAY[2]}
mese=${STR_ARRAY[1]}
giorno=${STR_ARRAY[0]}
dataok=$(LANG=en_EN date -Rd "$anno-$mese-$giorno")

# creo il corpo del feed RSS
echo "<item><title>$text</title><link>http://pubblicazioni.saga.it$href</link><pubDate>$dataok</pubDate></item>" >> $output/out.xml

done < $INPUT
IFS=$OLDIFS

# creo il feed RSS, facendo il merge di intestazione, corpo e piede
echo $intestazioneRSS >> $output/feed.xml
cat $output/out.xml >> $output/feed.xml
echo $chiusuraRSS >> $output/feed.xml

# rimuovo dal tag link estratto dall'albo di Pedara la parte di URL legata alla sessione, in quanto inutile
sed -i -e "s/;jsessionid=\([0-9]\|[a-zA-Z]\)\{10,1000\}//g" $output/feed.xml

# sostituisco i caratteri "&" e "=" presenti nel tag link, che danno problemi di encoding su Telegram e creo il feed che fa da trigger a Telegram
sed -e "s/&org=pedara/%26org%3Dpedara/g" $output/feed.xml > $output/feed_telegram.xml

# sostituisco il carattere "&" che non è compliant XML e creo il feed che fa da trigger a Telegram
sed -e "s/&org=pedara/\&amp;org=pedara/g" $output/feed.xml > $output/feed_rss.xml
