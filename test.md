---
layout: page
title: Comuni con Albo POP TCI
permalink: /comune/tci/
---

A seguire i comuni per cui è stata creata una versione **POP** del loro **Albo Pretorio**:


<ul class="listing">
		{% assign projects = site.comune | where: "tags", "tci" %}
		{% for project in projects %}
		<li>
			<a href="{{ project.url }}">{{ project.title }}</a>
		</li>
		{% endfor %}
</ul>