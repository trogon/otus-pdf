{%- assign namespace = page.apiname | split: "." -%}
{%- assign api_examples = site.data.examples -%}

{%- for part in namespace -%}
{%- assign api_examples = api_examples[part] -%}
{%- endfor -%}

{%- if api_examples -%}
<h3>Examples</h3>

{%- for example in api_examples %}
<p>{{ example[1].title }}</p>
{{ example[1].content | markdownify }}
{%- endfor %}

{%- endif -%}
