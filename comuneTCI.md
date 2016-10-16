layout: page
title: Comuni TCI con Albo POP
permalink: /comune/tci
---

A seguire i comuni per cui è stata creata una versione **POP** del loro **Albo Pretorio**:

{% assign tci = site.comune | where: "tags", "tci" %}
{% for comuni in tci %}
  ciao
{% endfor %}