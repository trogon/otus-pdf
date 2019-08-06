{%- assign namespace = page.apiname | split: "." -%}
{%- assign api_examples = site.data.examples -%}

{%- for part in namespace -%}
{%- assign api_examples = api_examples[part] -%}
{%- endfor -%}

{%- if api_examples -%}
<h3>Examples</h3>

<div class="accordion">
{%- for example in api_examples %}
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">
        <a class="btn-link" data-toggle="collapse" href="#{{ example[0] }}" role="button" aria-expanded="true" aria-controls="{{ example[0] }}">
          {{ example[1].title }}
        </a>
      </h5>
    </div>
    <div class="collapse show" id="{{ example[0] }}">
      <div class="card-body">
        <h5 class="card-title">{{ example[1].title }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{{ example[1].description }}</h6>
        <p class="card-text">{{ example[1].content | markdownify }}</p>
      </div>
    </div>
  </div>
{%- endfor %}
</div>

{%- endif -%}
