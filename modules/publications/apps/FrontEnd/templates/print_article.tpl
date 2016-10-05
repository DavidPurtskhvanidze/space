{title}{$category.title} :: {$article.title}{/title}
<div class="publicationArticle">
	<h1>{$article.title|escape}</h1>
	<p class="timestamp">
		<span class="fieldValue fieldValueTimestamp">[[$article.date]]</span>
	</p>
    {*{var_dump($article.text)}*}
	{$article.text}
</div>
<hr/>
{assign var="curUrl" value="{page_url id='news'}"|cat:$article.id}
{module name="facebook_comments" function="display_comments" url="$curUrl"}
