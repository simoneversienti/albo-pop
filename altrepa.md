---
layout: page
title: Altre Pubbliche Amministrazioni con Albo POP
permalink: /altrepa/
---

A seguire altre Pubbliche Amministrazioni per cui è stata creata una versione **POP** del loro **Albo Pretorio**:

{% for comune in site.altrepa %}
  <li><a href="{{ site.baseurl }}{{ altrepa.url }}">{{ altrepa.title }}</a></li>
{% endfor %}