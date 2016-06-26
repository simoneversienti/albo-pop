#!/bin/bash

cartella="/var/www/output/messina"
work='/var/nadir/work/messina'

#imposto il cookie
curl -s -c $cartella/cookies.txt  "http://88.41.28.53:8080/jalbopretorio/AlboPretorio"  > /dev/null 2>&1

#scarico la pagina
curl -s -b $cartella/cookies.txt 'http://88.41.28.53:8080/jalbopretorio/AlboPretorio'  -H 'Origin: http://88.41.28.53:8080' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4' -H 'Upgrade-Insecure-Requests: 1' -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.82 Safari/537.36' -H 'Content-Type: application/x-www-form-urlencoded' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' -H 'Cache-Control: max-age=0' -H 'Referer: http://88.41.28.53:8080/jalbopretorio/AlboPretorio' -H 'Connection: keep-alive' --data 'servizio=cerca&tipoPratica=&codiceRichiedente=&dadata=&adata=&oggetto=&ordina=ANNO_REGISTRO%2CNUMERO_REGISTRO&delXPag=100&sort=DESC&submit=Cerca' --compressed > $cartella/index.html

# estraggo titolo, link, e data dal file html e converto in csv
cat $cartella/index.html | /usr/local/bin/nadir/scrape -be '#mainContent > ul > li' | xml2json | jq '[.html.body.li[] | {titolo:.a["#text"],href:.a["@href"],data:.ul.li[2].span[1]["#text"]}]' | sed 's/\\n/ /g'| in2csv -f json | sed 's/                     //g' > $cartella/albomessina.csv

# estraggo titolo, link, e data dal file html e converto in csv
cat $cartella/index.html | /usr/local/bin/nadir/scrape -be '#mainContent > ul > li' | xml2json | jq '[.html.body.li[] | {titolo:.a["#text"],href:.a["@href"],data:.ul.li[2].span[1]["#text"]}]' | sed 's/\\n/ /g'| in2csv -f json | sed 's/                     //g' > $work/albomessina.csv




