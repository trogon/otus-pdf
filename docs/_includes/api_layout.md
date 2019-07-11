{%- assign namespace = page.apiname | split: "." -%}
{%- assign apidesc = site.data.api -%}

{%- for part in namespace -%}
{%- assign apidesc = apidesc[part] -%}
{%- endfor -%}

<h2>
    {{- apidesc.name -}}

{%- if apidesc.deprecated %}
    <span class="label label-red">Deprecated in {{ apidesc.deprecated }}</span>
{%- endif -%}

{%- if apidesc.introduced %}
    <span class="label label-purple">New in {{ apidesc.introduced }}</span>
{%- endif -%}

{%- if apidesc.version and apidesc.deprecated == nil %}
    <span class="label label-green">Version {{ apidesc.version }}</span>
{%- endif -%}
</h2>
<p>{{ apidesc.description }}</p>

<h3>Properties</h3>
<table>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Access</th>
        <th>Description</th>
    </tr>
{%- for property in apidesc.properties %}
    <tr>
        <td>
            <strong>${{- property.name -}}</strong>
{%- if property.introduced %}
            <span class="label label-purple">New in {{ property.introduced }}</span>
{%- endif -%}
        </td>
        <td>{{- property.type -}}</td>
        <td>{{- property.access -}}</td>
        <td>{{- property.description -}}</td>
    </tr>
{%- else %}
    <tr>
        <td>No properties</td>
    </tr>
{%- endfor %}
</table>

<h3>Methods</h3>
<table>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Description</th>
    </tr>
{%- for method in apidesc.methods %}
    <tr>
        <td>
            <strong>{{- method.name -}}()</strong>
{%- if method.introduced %}
            <span class="label label-purple">New in {{ method.introduced }}</span>
{%- endif -%}
        </td>
        <td>{{- method.type -}}</td>
        <td>{{- method.description -}}</td>
    </tr>
{%- else %}
    <tr>
        <td>No methods</td>
    </tr>
{%- endfor %}
</table>
