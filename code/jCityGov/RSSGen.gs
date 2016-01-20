/**
 * This code creates an RSS feed from the CSV link of the Albo Pretorio
 * bulletin board of a municipality which is using the JCity-Gov software
 * by Maggioli S.p.A. ( http://www.maggioli.it )
 * 
 * When published as a web app on Google Apps Script platform,
 * the code runs every time it is invoked and:
 * * downloads the CSV from the Albo Pretorio page
 * * creates and returns an RSS feed from the CSV
 *
 * Copyright 2016 Matteo Fortini
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Matteo Fortini
 */



alboURL='http://servizi.comune.cento.fe.it/web/trasparenza/albo-pretorio';
RSSFeedURL=alboURL;
RSSFeedTitle='AlboPOP';


I_PROP_DESCR=1;
I_OGGETTO=2;
I_ANNO=3;
I_NUMERO=4;
I_ANNO_REGISTRAZIONE=5;
I_NUM_REGISTRAZIONE=6;
I_ANNO_PROTOCOLLO=7;
I_NUMERO_PROTOCOLLO=8;
I_TITOLO_CAT=18
I_TITOLO_SOTTOCAT=19
I_DATA_INIZIO=20
I_DATA_FINE=21


var RSSCreator = function(){
        var ch = XmlService.createElement('channel');
        var root = XmlService.createElement('rss')
                .setAttribute('version', '2.0')
                .setAttribute('xmlnsatom', "http://www.w3.org/2005/Atom")
                .addContent(ch);

        var title = '';
        var link = '';
        var description = '';
        var language = '';
        var atomlink = '';
        var items = {};

        var createElement = function(element, text){
                return XmlService.createElement(element).setText(text);
        };


        return {
                setTitle: function(value){ title = value; },
                setLink: function(value){ link = value; },
                setDescription: function(value){ description = value; },
                setLanguage: function(value){ language = value; },
                setAtomlink: function(value){ atomlink = value; },

                addItem: function(args){
                        args.timezone = "GMT"; 

                        var item = {
                                title: args.title,
                                link: args.link,
                                description: args.description,
                                pubDate: Utilities.formatDate(args.pubDate, args.timezone, "EEE, dd MMM yyyy HH:mm:ss Z"),
                                guid: args.guid
                        }

                        items[item.guid] = item;
                },

                toString: function(){
                        ch.addContent(XmlService.createElement("atomlink")
                                        .setAttribute('href', atomlink)
                                        .setAttribute('rel', 'self')
                                        .setAttribute('type', 'application/rss+xml')
                                        );

                        ch.addContent(createElement('title', title));
                        ch.addContent(createElement('link', link));
                        ch.addContent(createElement('description', description));
                        ch.addContent(createElement('language', language));


                        for (var i in items) {
                                ch.addContent(
                                                XmlService
                                                .createElement('item')
                                                .addContent(createElement('title', items[i].title))
                                                .addContent(createElement('link', items[i].link))
                                                .addContent(createElement('description', items[i].description))
                                                .addContent(createElement('pubDate', items[i].pubDate))
                                                .addContent(createElement('guid', items[i].guid).setAttribute('isPermaLink','false'))
                                                );
                        }

                        var document = XmlService.createDocument(root);
                        var xml = XmlService.getPrettyFormat().format(document)
                                var result = xml.replace('xmlnsatom', 'xmlns:atom')
                                .replace('<atomlink href=','<atom:link href=');

                        return result;
                }
        };
};


function doGet() { 
        var alboCSV=alboURL+'?p_p_id=jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_resource_id=exportList&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&_jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet_format=csv';
        var feed =  UrlFetchApp.fetch(alboCSV).getContentText();

        // Eliminazione di tutti gli a-capo all'interno di una riga per non ingannare parseCsv
        var re=/^((?:(?:"(?:[^"]|"")*?")?,)*"(?:[^"]*?))[\n\r]((?:[^"]|"")*?)"/gm;
        feed=feed.replace(re,'$1 $2"');

        var rss = RSSCreator();

        var csvContent=Utilities.parseCsv(feed);  

        rss.setTitle(RSSFeedTitle);
        rss.setLink(RSSFeedURL);
        rss.setDescription('RSS 2.0 Feed');
        rss.setLanguage('it');
        rss.setAtomlink(alboURL);

        for (var i=1; i < csvContent.length; i++) {
                var riga=csvContent[i];
                var myguid=riga[I_ANNO_REGISTRAZIONE]+'/'+riga[I_NUM_REGISTRAZIONE]+' '+riga[I_TITOLO_CAT]+'/'+riga[I_TITOLO_SOTTOCAT];
                var descrizione=riga[I_OGGETTO]+' numero '+myguid+' dal '+riga[I_DATA_INIZIO]+ ' al '+riga[I_DATA_FINE]

                var pDate=riga[I_DATA_INIZIO]
                var pattern = /(\d{2})\/(\d{2})\/(\d{4})/;
                var pDateFix=pDate.replace(pattern,'$2/$1/$3')
                
                if (pDateFix.length > 0) {
                  var pubDateDate = new Date(pDateFix);
                } else {
                  var pubDateDate = new Date();
                }
                rss.addItem({title: riga[I_OGGETTO],
                             guid:encodeURIComponent(myguid),
                             link: alboURL,
                             description: descrizione,
                             pubDate: pubDateDate
                             });
        }

        var rssStr=rss.toString();
  
        return ContentService.createTextOutput(rssStr)
                .setMimeType(ContentService.MimeType.RSS);
}


