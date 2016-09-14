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
<title>AlboPOP - Comune - Genova</title>
```

## Tag link<a name="channel-link"></a>
L'URL diretto al feed.

```
<link>[...]</link>
```

## Tag description<a name="channel-description"></a>
La descrizione del feed. Deve essere nella forma `*non ufficiale* RSS feed dell'Albo Pretorio di [tipo pa] [nome pa]`. Esempio:

```
<description>*non ufficiale* RSS feed dell'Albo Pretorio del Comune di Genova</description>
```

## Tag language<a href="channel-language"></a>
La lingua dei contenuti del feed in formato [ISO 639-1](https://en.wikipedia.org/wiki/ISO_639-1). Esempio:

```
<language>it</language>
```

## Tag pubDate<a name="channel-pubdate"></a>
Data e orario dell'ultimo aggiornamento del feed, in formato conforme alle specifiche [RFC 822](https://www.w3.org/Protocols/rfc822/#z28).
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
URL al repository pubblico che contiene il codice dello scraper o alla pagina web che lo descrive. Esempio:

```
<docs>https://github.com/sabas/albopopGenova</docs>
```

## Tag copyright<a name="channel-copyright"></a>
L'indicazione del copyright dei contenuti del feed, nella forma `Copyright [anno], [nome pa]`. Esempio:

```
<copyright>Copyright 2016, Comune di Genova</copyright>
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
[under construction]
