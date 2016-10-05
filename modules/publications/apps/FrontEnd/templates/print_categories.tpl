{title}[[Publications]]{/title}
<h1>[[Publications]]</h1>
<span>[[Section list]]</span>
<ul>
{foreach from=$categories item="entry"}
	<li><a href="{page_path id='publications'}{$entry.id}/">{$entry.title|escape}</a></li>
{foreachelse}
	<li>[[There no categories available at this time]]</li>
{/foreach}
</ul>
