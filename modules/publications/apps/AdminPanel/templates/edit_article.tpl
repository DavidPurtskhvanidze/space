<div class="editArticle">
	<div class="breadcrumbs">
		<ul class="breadcrumb">
			<li><a href="{page_path id='publications'}">[[Publications]]</a>
				&gt; <a href="{page_path module='publications' function='edit_category'}?category_sid={$category.sid}"> {$category.title}</a>
				&gt; {$article.title}</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1 class="lighter">[[Edit the Article]]</h1>
		</div>
		{display_error_messages}

		{include file="publication_article_form.tpl" action='edit'}
	</div>
</div>
