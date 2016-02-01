from dbmanager import *

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/en/latest/topics/item-pipeline.html

class AlbopretorioPipeline(object):
    def open_spider(self, spider):
        self.dbInsertNuovi = dbInsertNuovi()
        pass

    def process_item(self, item, spider):
        print item
        self.dbInsertNuovi.add_item(item)
        return item

    def close_spider(self, spider):
        self.dbElaboraNuovi = dbElaboraNuovi()
        self.dbElaboraNuovi.elabora()
        pass
