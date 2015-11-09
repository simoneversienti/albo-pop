---
layout: page
title: Blog
permalink: /post/
---

{% for posts in site.posts %}
  <li><a href="{{ posts.baseurl }}/albo-pop{{ posts.url }}">{{ posts.title }}</a></li>
{% endfor %}