#!/bin/bash

### leggimi
# Questo è uno script bash che esegue l'URL encoding. E' utile ad esempio per 
# l'invio di dati a un canale Telegram, per i quali può essere necessario 
# (in presenza di determinati caratteri) farlo.

urlencode() {
    # urlencode <string>

    local LANG=C
    local length="${#1}"
    for (( i = 0; i < length; i++ )); do
        local c="${1:i:1}"
        case $c in
            [a-zA-Z0-9.~_-]) printf "$c" ;;
            *) printf '%%%02X' "'$c" ;; 
        esac
    done
}

### il codice sotto solo per fare un test
URL="http://www.comuneleonforte.it/index.php?option=com_chronocontact&chronoformname=atto_10_mostra&valore=9207&Itemid=62%27%22"

urlencode $URL;