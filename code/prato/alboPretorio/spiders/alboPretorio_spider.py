import scrapy
from scrapy import Spider
from scrapy.selector import Selector
from scrapy.spiders import CrawlSpider, Rule
from scrapy.linkextractors import LinkExtractor
from scrapy.http import FormRequest, Request

from datetime import date,timedelta

from alboPretorio.items import AlbopretorioItem, AlbopretorioPDF

import re

link_dettaglio_re=re.compile(re.compile('(http.*?);'))

from ConfigParser import ConfigParser

cp=ConfigParser()
cp.read('alboPretorio.cfg')

ALBO_BASE_URL=cp.get('settings','ALBO_BASE_URL')

class alboPretorioSpider(Spider):
    name = "alboPretorio"
    allowed_domains = ["albopretorio.comune.prato.it"]

    def start_requests(self):
	oggi=date.today()
	domani=oggi+timedelta(days=1)
	primogiorno=oggi-timedelta(days=2)
	
        return [ FormRequest('http://albopretorio.comune.prato.it/albopretoriobinj/AlboPretorio',
                     formdata={'servizio': 'cerca', 
                               'tipoPratica': '', 
                               'codiceRichiedente': '', 
                               'dadata': primogiorno.strftime('%d/%m/%Y'),
                               'adata' : domani.strftime('%d/%m/%Y'),
                               'oggetto': '', 
                               'ordina' : 'TIPO_PRATICA,ANNO_REGISTRO,NUMERO_REGISTRO', 
                               'delXPag': '50',
			       'sort':'ASC',
                               'submit':'Cerca'
                              },
                     callback=self.parse_start_url) ]

#curl 'http://albopretorio.comune.prato.it/albopretoriobinj/AlboPretorio' -H 'Cookie: JSESSIONID=7EE8004D6AC84477E15A48D5FF76FF39' -H 'Origin: http://albopretorio.comune.prato.it' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4' -H 'Upgrade-Insecure-Requests: 1' -H 'User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36' -H 'Content-Type: application/x-www-form-urlencoded' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' -H 'Cache-Control: max-age=0' -H 'Referer: http://albopretorio.comune.prato.it/albopretoriobinj/' -H 'Connection: keep-alive' -H 'DNT: 1' --data 'servizio=cerca&tipoPratica=&codiceRichiedente=&dadata=29%2F01%2F2016&adata=30%2F01%2F2016&oggetto=&ordina=TIPO_PRATICA%2CANNO_REGISTRO%2CNUMERO_REGISTRO&delXPag=10&sort=ASC&submit=Cerca' --compressed
#    start_urls= [
#		ALBO_BASE_URL,
#            ]

    def parse_start_url(self,response):
	self.log("PARSE")
	pagine=set([ALBO_BASE_URL+'/'+p for p in response.xpath('//a[contains(@title,"Vai alla pagina")]/@href').extract()])
	items=[Request(pag,callback=self.parse_albo) for pag in pagine]
	items += self.parse_albo(response)
	return items

    def parse_albo(self, response):
	self.log("PARSE_ALBO")
        sel = Selector(response)

	items = []

	voci=sel.xpath('//li[a[@title="Vai al testo integrale"]]')
	self.logger.info("Found %d rows" % (len(voci),))
	for v in voci:
	    item = AlbopretorioItem()
            try:
		oggetto=v.xpath('.//a/text()').extract()[0]
		annonumero=v.xpath('.//li[span[text()="Registro"]]/span[not(@class="grassetto")]/text()').extract()[0]
		(id_registro_anno,id_registro_num)=annonumero.split('/')

		tipo_atto=v.xpath('.//li[span[text()="Tipologia"]]/span[not(@class="grassetto")]/text()').extract()[0]

		(data_inizio_pub,data_fine_pub)=v.xpath('.//li[span[contains(text(),"In pubblicazione")]]/span[not(@class="grassetto")]/text()').extract()
		link_dettaglio=ALBO_BASE_URL+'/'+v.xpath('.//a/@href').extract()[0]

		item['id_registro_anno']=id_registro_anno.strip()
		item['id_registro_num']=id_registro_num.strip()
		item['tipo_atto']=tipo_atto.strip()
		item['oggetto']=oggetto.strip()
		item['data_inizio_pub']=data_inizio_pub.strip()
		item['data_fine_pub']=data_fine_pub.strip()
		item['link_dettaglio']=link_dettaglio

                items.append(item)
            except IndexError, e:
		self.log("ERROR %s" %(e,))
                pass

	pagine=set([ALBO_BASE_URL+'/'+p for p in sel.xpath('//a[contains(@title,"Vai alla pagina")]/@href').extract()])
	items+=[Request(pag,callback=self.parse_albo) for pag in pagine]

	return items

