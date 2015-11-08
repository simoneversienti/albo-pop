---
layout: default
title: Comuni con Albo POP
permalink: /comune/
---

{% for piece in site.comune %}
  <li>

    <a href="{{ piece.url }}">{{ piece.title }}</a>
  </li>
{% endfor %}