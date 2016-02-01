# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

from scrapy.item import Item, Field

class AlbopretorioItem(Item):
    # define the fields for your item here like:
    # name = Field()
    id_registro_anno = Field()
    id_registro_num  = Field()
    tipo_atto        = Field()
    oggetto          = Field()
    data_inizio_pub  = Field()
    data_fine_pub    = Field()
    tipo_documento   = Field()
    link_dettaglio   = Field()
    #documenti        = Field()
    #titolo           = Field()
    file_urls        = Field()
    files            = Field()

class AlbopretorioPDF(Item):
    # define the fields for your item here like:
    # name = Field()
    url = Field()
