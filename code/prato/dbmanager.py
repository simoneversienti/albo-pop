#!/usr/bin/env python
# -*- coding: utf-8 -*-
import sqlite3
 
import telegram
import tweepy, time, sys
import re
import os
import magic
import mimetypes

from ConfigParser import ConfigParser

 
cp=ConfigParser()
cp.read('alboPretorio.cfg')

CONSUMER_KEY=cp.get('twitter','CONSUMER_KEY')
CONSUMER_SECRET=cp.get('twitter','CONSUMER_SECRET')
ACCESS_KEY=cp.get('twitter','ACCESS_KEY')
ACCESS_SECRET=cp.get('twitter','ACCESS_SECRET')
TELEGRAM_TOKEN=cp.get('telegram','TELEGRAM_TOKEN')
TELEGRAM_CHANNEL=cp.get('telegram','TELEGRAM_CHANNEL')
MAX_AGE=int(cp.get('settings','MAX_AGE'))
FILES_BASE_PATH=cp.get('settings','FILES_BASE_PATH')
 
def postTweet(statusline):
    auth = tweepy.OAuthHandler(CONSUMER_KEY, CONSUMER_SECRET)
    auth.set_access_token(ACCESS_KEY, ACCESS_SECRET)
    api = tweepy.API(auth)

    api.update_status(status=statusline)
    time.sleep(2)

def postTelegram(statusline):
	bot = telegram.Bot(token=TELEGRAM_TOKEN)
	try:
		bot.sendMessage(chat_id='@'+TELEGRAM_CHANNEL,text=statusline)
	except KeyError:
		pass
 
def postTelegramFile(filename, path):
	bot = telegram.Bot(token=TELEGRAM_TOKEN)
	print("send", path, "as", filename)
	mm=magic.from_file(path, mime=True)
	ext=mimetypes.guess_extension(mm)
	filename=filename.encode('ascii')
	filename+=ext
	try:
		bot.sendDocument(chat_id='@'+TELEGRAM_CHANNEL,document=open(path,'rb'),filename=filename)
	except KeyError:
		pass

class dbInsertNuovi():
    def __init__(self,name='alboPretorio'):
        self.db = sqlite3.connect(name+'.sqlite3')
        self.cur = self.db.cursor()
        self.cur.execute('''CREATE TABLE IF NOT EXISTS alboPretorio (
                      id_registro_anno NUMERIC NOT NULL,
                      id_registro_num NUMERIC NOT NULL,
                      tipo_atto TEXT NOT NULL,
                      oggetto TEXT NOT NULL,
                      data_inizio_pub TEXT NOT NULL,
                      data_fine_pub   TEXT NOT NULL,
                      link_dettaglio  TEXT NOT NULL,
		      tweeted         BOOLEAN DEFAULT FALSE NOT NULL,
                      telegrammed     BOOLEAN DEFAULT FALSE NOT NULL,
                      age             INTEGER DEFAULT 0 NOT NULL,
                      PRIMARY KEY (id_registro_anno, id_registro_num, tipo_atto)
                      );''')
        self.cur.execute('''CREATE TABLE IF NOT EXISTS alboPretorio_files (
                      id_registro_anno NUMERIC NOT NULL,
                      id_registro_num NUMERIC NOT NULL,
                      tipo_atto TEXT NOT NULL,
                      titolo TEXT NOT NULL,
                      path TEXT NOT NULL,
                      PRIMARY KEY (id_registro_anno, id_registro_num, tipo_atto, path)
                      );''')

        self.db.commit()

    def __del__(self):
        self.db.close()
            

    def add_item(self,item):
	try:
		# insert into albo pretorio only if not already there
		res = self.cur.execute('SELECT count(*) FROM alboPretorio WHERE id_registro_anno = ? AND id_registro_num = ? AND tipo_atto = ?', (item['id_registro_anno'],item['id_registro_num'], item['tipo_atto']))
		count = res.fetchone()[0]
		if count == 0:
		    self.cur.execute ('''INSERT INTO alboPretorio(
					 id_registro_anno,
					 id_registro_num,
				         tipo_atto,
					 oggetto,
					 data_inizio_pub,
					 data_fine_pub,
                                         link_dettaglio)
					 VALUES (?,
					 ?,
					 ?,
					 ?,
					 ?,
					 ?,
					 ?)
					 ''', (
					     item['id_registro_anno'],
					     item['id_registro_num'],
					     item['tipo_atto'],
					     item['oggetto'],
					     item['data_inizio_pub'],
					     item['data_fine_pub'],
					     item['link_dettaglio'],
					     ))
		    self.db.commit()
		res = self.cur.execute('UPDATE alboPretorio SET age=0 WHERE id_registro_anno = ? AND id_registro_num = ? AND tipo_atto = ?', (item['id_registro_anno'],item['id_registro_num'],item['tipo_atto']))
	        self.db.commit()
	except Exception, e:
		print "Exception %s"%(e,)
		pass

	if len(item['files']) > 0:
		for f in item['files']:
			res = self.cur.execute('SELECT count(*) FROM alboPretorio_files WHERE id_registro_anno = ? AND id_registro_num = ? AND tipo_atto = ? AND path = ?', (item['id_registro_anno'],item['id_registro_num'],item['tipo_atto'],f['path']))
			count = res.fetchone()[0]
			if count == 0:
			    self.cur.execute ('''INSERT INTO alboPretorio_files(
						 id_registro_anno,
						 id_registro_num,
						 tipo_atto,
						 titolo,
						 path)
						 VALUES (?,
						 ?,
						 ?,
						 ?,
						 ?)
						 ''', (
						     item['id_registro_anno'],
						     item['id_registro_num'],
						     item['tipo_atto'],
						     item['titolo'],
						     f['path']))
			    self.db.commit()

                    

class dbElaboraNuovi():
    def __init__(self,name='alboPretorio'):
        self.db = sqlite3.connect(name+'.sqlite3')
        self.cur = self.db.cursor()
        self.db.commit()

    def abbrevia_oggetto(self, oggetto):
        oggetto = re.sub(r'\bprocedura\b','proced', oggetto)
        oggetto = re.sub(r'\bpubblicazion(e|i)\b','pubbl', oggetto)
        oggetto = re.sub(r'\bservizio?\b','serv', oggetto)
        oggetto = re.sub(r'\bprofessional(e|i)\b','prof', oggetto)
        oggetto = re.sub(r'\bdisposizion(e|i)\b','disp', oggetto)
        oggetto = re.sub(r'\bapprovazione\b','approv', oggetto)
        oggetto = re.sub(r'\bordinanz(a|e)\b','ordin', oggetto)
        oggetto = re.sub(r'\bnumer(o|i)\b','num', oggetto)
        oggetto = re.sub(r'\bpretorio\b','pret', oggetto)
        oggetto = re.sub(r'\bcivic(o|i)\b','civ', oggetto)
        oggetto = re.sub(r'\bcontribut(o|i)\b','contrib', oggetto)
        oggetto = re.sub(r'\bcollaborazion(e|i)\b','collab', oggetto)
        oggetto = re.sub(r'\bdiviet(o|i)\b','div', oggetto)
        oggetto = re.sub(r'\bregolament(o|i)\b','regolam', oggetto)
        oggetto = re.sub(r'\bprovincia\b','prov', oggetto)
        oggetto = re.sub(r'\bcomune\b','com', oggetto)
        oggetto = re.sub(r'\bautorizzazion(e|i)\b','aut', oggetto)
        oggetto = re.sub(r'\bpersonal(e|i)\b','pers', oggetto)
        oggetto = re.sub(r'\bdeposit(o|i)\b','depos', oggetto)
        oggetto = re.sub(r'\bamministrazion(e|i)\b','amm', oggetto)
        oggetto = re.sub(r'\?','', oggetto)
        return oggetto

    def do_tweet(self,id_registro_num,id_registro_anno, tipo_atto, oggetto,link_dettaglio):
	oggetto_abbr = oggetto.lower()
	oggetto_abbr = self.abbrevia_oggetto(oggetto_abbr)
	statusline = '[%s/%s]%s' % (str(id_registro_num), str(id_registro_anno),oggetto_abbr)
	statusline = statusline[:109]+'..' if len(statusline) > 109 else statusline
	statusline += " " + link_dettaglio
	try:
	    self.curWrite.execute ('''UPDATE alboPretorio SET tweeted = 1
				 WHERE id_registro_anno = ? AND id_registro_num = ? AND tipo_atto = ?
			     ''', (
				 id_registro_anno,
				 id_registro_num,
				 tipo_atto
				 )
			     )

	    print("Tweeting %s" % (statusline,))
	    try:
		    postTweet(statusline)
		    self.db.commit()
	    except Exception,e: 
		    print("ERROR TWITTER (%s)" % (e,))
	except Exception,e: 
	    print("ERROR TWEETING (%s)" % (e,))
	    self.db.rollback()

    def do_telegram(self,id_registro_num,id_registro_anno, tipo_atto, oggetto,link_dettaglio):
        curFiles = self.db.cursor()
	tgLine = '[%s/%s %s] %s %s' % (str(id_registro_num), str(id_registro_anno), tipo_atto, oggetto, link_dettaglio)
	try:
	    self.curWrite.execute ('''UPDATE alboPretorio SET telegrammed = 1
				 WHERE id_registro_anno = ? AND id_registro_num = ? AND tipo_atto = ?
			     ''', (
				 id_registro_anno,
				 id_registro_num,
				 tipo_atto
				 )
			     )
	    try:
		    postTelegram(tgLine)
		    self.db.commit()
	    except Exception,e: 
		    print("ERROR TELEGRAM (%s)" % (e,))

	    resFiles  = curFiles.execute('SELECT titolo, path FROM alboPretorio_files WHERE id_registro_anno = ? AND id_registro_num = ? AND tipo_atto = ?', (id_registro_anno, id_registro_num, tipo_atto))
	    rowsFiles = curFiles.fetchall()
	    for rowFiles in rowsFiles:
		    (titolo,path) = rowFiles
		    titoloFile = titolo
		    postTelegramFile(titoloFile, os.path.join(FILES_BASE_PATH,path))
	except Exception,e: 
	    print("ERROR TELEGRAMMING (%s)" % (e,))
	    self.db.rollback()

    def elabora(self):
	    self.curWrite = self.db.cursor()
            res = self.cur.execute('SELECT id_registro_num, id_registro_anno, tipo_atto, oggetto,data_inizio_pub,data_fine_pub,link_dettaglio FROM alboPretorio WHERE NOT tweeted ORDER BY id_registro_anno, id_registro_num, tipo_atto')
            rows=self.cur.fetchall()
            for row in rows:
                (id_registro_num, id_registro_anno, tipo_atto, oggetto,data_inizio_pub,data_fine_pub,link_dettaglio) = row
		self.do_tweet(id_registro_num, id_registro_anno, tipo_atto, oggetto,link_dettaglio)

            res = self.cur.execute('SELECT id_registro_num, id_registro_anno, tipo_atto, oggetto,data_inizio_pub,data_fine_pub,link_dettaglio FROM alboPretorio WHERE NOT telegrammed ORDER BY id_registro_anno, id_registro_num, tipo_atto')
            rows=self.cur.fetchall()
            for row in rows:
		(id_registro_num, id_registro_anno, tipo_atto, oggetto,data_inizio_pub,data_fine_pub,link_dettaglio) = row
		self.do_telegram(id_registro_num, id_registro_anno, tipo_atto, oggetto, link_dettaglio)
            res = self.cur.execute('UPDATE alboPretorio SET age = age+1')
            res = self.cur.execute('DELETE FROM alboPretorio WHERE age > ?', (MAX_AGE,))

	    self.db.commit()

            

    def __del__(self):
        self.db.close()


