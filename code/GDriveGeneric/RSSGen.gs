/**
 * A Google Apps Script to generate an RSS from a correctly formatted GDrive spreadsheet
 * 
 * @author Matteo Fortini
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
 */

/* This script assumes to use a spreadsheet with this form:
 *
 * - A sheet named "clean" in which there are three columns, with a header in row 1:
 *   - TITLE: the title of the record
 *   - PUBDATE: the publication date in a format parseable by JavaScript's Date() function
 *   - HREF: the link to the news
 * - A sheet named "meta" in which:
 *   - cell G1 will be used as the RSS feed title
 *   - cell G2 will be used as the RSS feed URL
 *   - cell G3 will be used as the RSS feed description
 *
 * The ID for the spreadsheet is a code that can be extracted from GDrive URL, e.g.
 * for the URL https://docs.google.com/spreadsheets/d/1VgASeOEyGKvpvMa8yWHBWsobgQw4hUK42xtMQvlO7Po/edit
 * the ID is 1VgASeOEyGKvpvMa8yWHBWsobgQw4hUK42xtMQvlO7Po
 */

ID_SPREADSHEET="INSERT HERE SPREADSHEET ID (CAN BE EXTRACTED FROM GDRIVE URL)";

I_TITLE=0;
I_PUBDATE=1;
I_HREF=2;

var makeRss = function(){
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


                        ch.addContent(XmlService.createElement("xhtmlmeta")
                                        .setAttribute('xmlnsxhtml','http://www.w3.org/1999/xhtml')
                                        .setAttribute('name','robots')
                                        .setAttribute('content','noindex')
                                        );

                        for (var i in items) {
                                ch.addContent(
                                                XmlService
                                                .createElement('item')
                                                .addContent(createElement('title', items[i].title))
                                                .addContent(createElement('link', items[i].link))
                                                .addContent(createElement('description', items[i].description))
                                                .addContent(createElement('pubDate', items[i].pubDate))
                                                .addContent(createElement('guid', items[i].guid))
                                                );
                        }

                        var document = XmlService.createDocument(root);
                        var xml = XmlService.getPrettyFormat().format(document)
                                var result = xml.replace('xmlnsatom', 'xmlns:atom')
                                .replace('<atomlink href=','<atom:link href=')
                                .replace('xhtmlmeta','xhtml:meta')
                                .replace('xmlnsxhtml','xmlns:xhtml');

                        return result;
                }
        };
};


function doGet() { 
  var ss = SpreadsheetApp.openById(ID_SPREADSHEET);
  
  var metaSheet = ss.getSheetByName('meta');
  
  var RSSFeedTitle = metaSheet.getRange('G1').getValue();
  var RSSFeedURL = decodeURIComponent(metaSheet.getRange('G2').getValue().trim());
  var RSSFeedDesc = metaSheet.getRange('G3').getValue();

  var dataSheet = ss.getSheetByName('clean');
  
  var rss=makeRss();
 
  rss.setTitle(RSSFeedTitle);
  rss.setLink(RSSFeedURL);
  rss.setDescription(RSSFeedDesc);
  rss.setLanguage('it');
  rss.setAtomlink(RSSFeedURL);

  for (var i=2; i < 1000; i++) {
    var riga=dataSheet.getRange(i,1,1,3).getValues();
    var myguid=riga[0][I_HREF];
    var titolo=riga[0][I_TITLE];
    var pDate=riga[0][I_PUBDATE]; 
    
    //Logger.log('Riga ' + i + ' myguid ' + myguid);
    if (myguid.length == 0) {
      break;
    }
    
    //var pattern = /(\d{2})\/(\d{2})\/(\d{4})/;
    //var pDateFix=pDate.replace(pattern,'$2/$1/$3')
    var pDateFix=pDate;
    
    if (pDateFix.length > 0) {
      var pubDateDate = new Date(pDateFix);
    } else {
      var pubDateDate = new Date();
    }
    
    rss.addItem({title: titolo,
                 guid:myguid,
                 link: myguid,
                 description: titolo,
                 pubDate: pubDateDate
                             });
        }
  
  var rssStr=rss.toString();
  
  //Logger.log(rssStr)
  
  return ContentService.createTextOutput(rssStr).setMimeType(ContentService.MimeType.RSS);
}



