---
layout: page
title: Specifiche dei feed RSS per AlboPOP
permalink: /specs/
---

Gli scraper di AlboPOP producono un [feed RSS](/feedrss/) il cui formato standard segue delle
[specifiche precise](https://cyber.harvard.edu/rss/rss.html).
Quelle che seguono sono le regole condivise per produrre feed dal formato omogeneo, valido per tutti gli albi pretori di origine.
I tag e gli attributi elencati qui sono da considerarsi *obbligatori* per i feed di AlboPOP.
Altri elementi e attributi previsti dalle specifiche o da namespace aggiuntivi sono possibili, ma da considerarsi facoltativi.
Gli URL devono essere tutti riferimenti *assoluti* (http:// ecc.).

# Intestazione XML<a name="xml"></a>
La prima riga del feed deve contenere l'indicazione della versione dell'xml e dell'encoding.

```
<?xml version="1.0" encoding="UTF-8"?>
```

# Tag rss<a name="rss"></a>
L'elemento radice deve essere il tag `rss` con l'indicazione della versione *e degli eventuali namespace*.

```
<rss
 xmlns:creativeCommons="http://cyber.law.harvard.edu/rss/creativeCommonsRssModule.html"
 xmlns:xhtml="http://www.w3.org/1999/xhtml"
 version="2.0">
[...]
</rss>
```

# Tag channel<a name="channel"></a>
L'elemento figlio di [rss](#rss) deve essere l'elemento `channel`, senza attributi.

```
<channel>[...]</channel>
```

## Tag title<a name="channel-title"></a>
Il titolo del feed deve essere nella forma `AlboPOP - [tipo pa] - [nome pa]`. Esempio:

```
<title>AlboPOP - Comune - Bagheria</title>
```

## Tag link<a name="channel-link"></a>
L'URL diretto al feed.

```
<link>[...]</link>
```

## Tag description<a name="channel-description"></a>
La descrizione del feed. Deve essere nella forma `*non ufficiale* RSS feed dell'Albo Pretorio di [tipo pa] [nome pa]`. Esempio:

```
<description>*non ufficiale* RSS feed dell'Albo Pretorio del Comune di Bagheria</description>
```

## Tag language<a href="channel-language"></a>
La lingua dei contenuti del feed in formato [ISO 639-1](https://en.wikipedia.org/wiki/ISO_639-1). Esempio:

```
<language>it</language>
```

## Tag pubDate<a name="channel-pubdate"></a>
Data e orario dell'ultimo aggiornamento del feed (per esempio, dell'ultima esecuzione dello scraper), in formato conforme alle specifiche [RFC 822](https://www.w3.org/Protocols/rfc822/#z28).
Esempio:

```
<pubDate>Tue, 10 Jul 2016 04:00:00 GMT</pubDate>
```

## Tag webMaster<a name="channel-webmaster"></a>
Nome ed email del curatore del feed e/o autore dello scraper. Esempio:

```
<webMaster>john@smith.com (John Smith)</webMaster>
```

## Tag docs<a name="channel-docs"></a>
URL al repository pubblico che contiene il codice dello scraper o alla pagina web che lo descrive (per esempio, http://albopop.it/[tipo pa]/[nome pa]/). Esempio:

```
<docs>http://albopop.it/comune/bagheria/</docs>
```

## Tag copyright<a name="channel-copyright"></a>
L'indicazione del copyright dei contenuti del feed, nella forma `Copyright [anno], [nome pa]`. Esempio:

```
<copyright>Copyright 2016, Comune di Bagheria</copyright>
```

## Tag creativeCommons:license<a name="channel-cc"></a>
L'indicazione del copyleft dei contenuti del feed.

```
<creativeCommons:license>http://creativecommons.org/licenses/by/3.0/</creativeCommons:license>
```

## Tag xhtml:meta<a name="channel-xhtml-meta"></a>
Necessario per evitare l'indicizzazione da motore di ricerca delle pagine linkate nel feed.

```
<xhtml:meta name="robots" content="noindex" />
```

# Tag item<a name="item"></a>
Sono gli elementi che rappresentano un singolo atto dell'albo pretorio. Possono essere zero (feed vuoto) o più, fino a 25, in ordine inverso di data e orario di pubblicazione.

```
<item>[...]</item>
```

## Tag title<a name="item-title"></a>
Il titolo dell'atto, così come riportato dalla pagina dedicata dell'albo pretorio. Si sconsiglia ogni intervento sul testo, come riduzione in lettere minuscole, a parte l'eliminazione di spazi e tabulazioni consecutivi e/o agli estremi della stringa.

```
<title>[...]</title>
```

## Tag link<a name="item-link"></a>
L'URL diretto alla pagina ufficiale dell'atto. Non a un documento (un file pdf, per esempio, vedi il tag [enclosure](#item-enclosure)), ma a una pagina web.

```
<link>[...]</link>
```

## Tag description<a name="item-description"></a>
L'excerpt dell'atto, così come riportato in forma sintetica nella pagina ufficiale. Se mancante, una copia del titolo. Si sconsiglia ogni intervento sul testo, come riduzione in lettere minuscole, a parte l'eliminazione di spazi e tabulazioni consecutivi e/o agli estremi della stringa.

```
<description>[...]</description>
```

## Tag pubDate<a name="item-pubdate"></a>
Data e orario ufficiali di pubblicazione dell'atto all'interno dell'Albo Pretorio, in formato conforme alle specifiche [RFC 822](https://www.w3.org/Protocols/rfc822/#z28). Deve essere indipendente da data e orario di scraping (che sono riportati nel [tag pubDate del tag channel](#channel-pubdate)).

```
<pubDate>[...]</pubDate>
```

## Tag guid<a name="item-guid"></a>
Identificativo unico *universale* dell'atto. Non può essere il semplice id dell'atto (che non è universalmente unico), generalmente è uguale all'URL diretto alla pagina ufficiale (in questo caso deve contenere l'attributo `isPermalink`).

```
<guid isPermaLink="true">[...]</guid>
```

## Tag category<a name="item-category"></a>
Devono essere due o più e contenere il maggior numero possibile delle seguenti informazioni:

* nazione pa,
* regione pa,
* provincia pa,
* comune pa,
* tipo pa,
* nome pa,
* latitudine,
* longitudine,
* codice univoco con prefisso.

### Domain country<a name="item-category-country"></a>
La nazione a cui appartiene la pa che emette l'atto. Esempio:

```
<category domain="http://albopop.it/specs#item-category-country">Italia</category>
```

### Domain region<a name="item-category-region"></a>
La regione a cui appartiene la pa che emette l'atto. Esempio:

```
<category domain="http://albopop.it/specs#item-category-region">Liguria</category>
```

### Domain province<a name="item-category-province"></a>
La provincia a cui appartiene la pa che emette l'atto. Esempio:

```
<category domain="http://albopop.it/specs#item-category-province">Genova</category>
```

### Domain municipality<a name="item-category-municipality"></a>
Il comune a cui appartiene la pa che emette l'atto. Esempio:

```
<category domain="http://albopop.it/specs#item-category-municipality">Genova</category>
```

### Domain type<a name="item-category-type"></a>
Il tipo di pa che emette l'atto. Esempio:

```
<category domain="http://albopop.it/specs#item-category-type">Comune</category>
```

### Domain name<a name="item-category-name"></a>
Il nome della pa che emette l'atto. Esempio:

```
<category domain="http://albopop.it/specs#item-category-name">Comune di Genova</category>
```

### Domain latitude<a name="item-category-latitude"></a>
La latitudine della pa che emette l'atto. Quella del comune di riferimento in mancanza di una sede specifica.

```
<category domain="http://albopop.it/specs#item-category-latitude">[...]</category>
```

### Domain longitude<a name="item-category-longitude"></a>
La longitudine della pa che emette l'atto. Quella del comune di riferimento in mancanza di una sede specifica.

```
<category domain="http://albopop.it/specs#item-category-longitude">[...]</category>
```

### Domain uid<a name="item-category-uid"></a>
L'identificativo univoco della pa che emette l'atto, con un prefisso che ne indica il database di riferimento.
Per un comune valgono per esempio i codici ISTAT. Esempio:

```
<category domain="http://albopop.it/specs#item-category-uid">istat:010025</category>
```

## Tag enclosure<a name="item-enclosure"></a>
Uno o più allegati con l'URL diretto all'atto integrale, generalmente un file pdf. Esempio:

```
<enclosure url="[...]" length="[...]" type="application/pdf" />
```

# Esempio completo
Qui di seguito è riportato un esempio completo di feed con un solo elemento a scopo dimostrativo.

```
<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:creativeCommons="http://cyber.law.harvard.edu/rss/creativeCommonsRssModule.html" xmlns:xhtml="http://www.w3.org/1999/xhtml" version="2.0">
 <channel>
  <title>AlboPOP - Comune - Bagheria</title>
  <link>https://script.google.com/macros/s/AKfycbxiqe9sZ7Y1yT8dm3diccl0EBhGAQ5ZF60Stq8SgM4qSIabfeA/exec</link>
  <description>*non ufficiale* RSS feed dell'Albo Pretorio del Comune di Bagheria</description>
  <language>it</language>
  <pubDate>Tue, 10 Jul 2016 04:00:00 GMT</pubDate>
  <webMaster>john@smith.com (John Smith)</webMaster>
  <docs>http://albopop.it/comune/bagheria/</docs>
  <creativeCommons:license>http://creativecommons.org/licenses/by/3.0/</creativeCommons:license>
  <xhtml:meta name="robots" content="noindex" />
 
  <category domain="comune">Amatrice</category>
  <category domain="provincia">Rieti</category>
  <category domain="regione">Lazio</category>
  <category domain="latitudine">42.629381</category>
  <category domain="longitudine">13.288372</category>
  <category domain="tipologia_pa">Comune</category>
 
  <item>
   <title>VINCOLO IDROGEOLOGICO. SIG. RAPONI SERGIO.</title>
   <link>http://halleyweb.com/c057002/mc/mc_gridev_dettaglio.php?x=&amp;interno=1&amp;id_pubbl=5772</link>
   <description>VINCOLO IDROGEOLOGICO. SIG. RAPONI SERGIO.</description>
   <pubDate>Mon, 22 Aug 2016 10:00:00 +0000</pubDate>
   <guid>http://halleyweb.com/c057002/mc/mc_gridev_dettaglio.php?x=&amp;interno=1&amp;id_pubbl=5772</guid>
 
   <category domain="custom">Post-terremoto 24 agosto 2016</category>
 
  </item>
 </channel>
</rss>
```
