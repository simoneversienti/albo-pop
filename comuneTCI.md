layout: page
title: Comuni con Albo POP
permalink: /comune/tci
---

A seguire i comuni per cui è stata creata una versione **POP** del loro **Albo Pretorio**:

{% assign tci = site.comune | where: "tags", "tci" %}
{% for comune in tci %}
  <li><a href="{{ site.baseurl }}{{ comune.url }}">{{ comune.title }}</a></li>
{% endfor %}