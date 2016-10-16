---
layout: page
title: Comuni con Albo POP TCI
permalink: /test/
---

A seguire i comuni per cui è stata creata una versione **POP** del loro **Albo Pretorio**:


<ul class="listing">
		{% assign projects = site.comune %}
		{% for project in projects %}
		<li>
			<h2><a href="{{ project.url }}">{{ project.title }}</a></h2>
		</li>
		{% endfor %}
</ul>