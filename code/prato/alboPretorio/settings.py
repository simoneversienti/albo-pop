# Scrapy settings for alboPretorio project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/en/latest/topics/settings.html
#
from ConfigParser import ConfigParser

 
cp=ConfigParser()
cp.read('alboPretorio.cfg')

FILES_BASE_PATH=cp.get('settings','FILES_BASE_PATH')

BOT_NAME = 'alboPretorio'

SPIDER_MODULES = ['alboPretorio.spiders']
NEWSPIDER_MODULE = 'alboPretorio.spiders'

ITEM_PIPELINES = {
    'alboPretorio.pipelines.AlbopretorioPipeline': 300,
    'scrapy.pipelines.files.FilesPipeline'                 : 1  ,
}

FILES_STORE = FILES_BASE_PATH

# Crawl responsibly by identifying yourself (and your website) on the user-agent
#USER_AGENT = 'alboPretorio (+http://www.yourdomain.com)'
DOWNLOAD_HANDLERS = {
  's3': None,
}

COOKIES_DEBUG = True
