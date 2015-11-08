---
layout: page
title: Comuni con Albo POP
permalink: /comune/
---

{% for comune in site.comune %}
  <li>

    <a href="{{ comune.url }}">{{ comune.title }}</a>
  </li>
{% endfor %}