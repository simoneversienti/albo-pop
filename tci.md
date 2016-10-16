---
layout: page
title: Comuni con Albo POP - Terremoto Centro Italia
permalink: /comune/tci/
---

A seguire l'elenco dei comuni coinvolti nel terremoto del centro Italia di fine agosto del 2016, per i quali è stato creato un **albo POP**:


<ul class="listing">
		{% assign comunitci = site.comune | where: "tags", "tci" %}
		{% for comunetci in comunitci %}
		<li>
			<a href="{{ comunetci.url }}">{{ comunetci.title }}</a>
		</li>
		{% endfor %}
</ul>

La creazione degli albi POP per questi comuni è stata realizzata dai soci dell'[associazione onData](http://ondata.it/): uno speciale grazie a Alessio Cimarelli e a Enrico Bergamini.
Ed è anche un piccolo contributo in sostegno del progetto [Terremoto Centro Italia](http://terremotocentroitalia.info/).