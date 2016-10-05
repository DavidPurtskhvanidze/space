<form method="get" action="{page_path id='search_results'}">
    <div class="quickSearchForm">
		<input type="hidden" name="action" value="search" />
		{if $GLOBALS.settings.autocomplete_enable_in_keyword_search}
			{search property=$form_fields.keywords.id template="string_with_autocomplete.tpl" parameters=['element_id_prefix'=>'quickSearch','preselection_fields'=>['category_sid']]}
		{else}
			{search property=$form_fields.keywords.id}
		{/if}
		&nbsp; [[in]] &nbsp; {search property=$form_fields.category_sid.id template="category_tree_noredirect.tpl"}
		&nbsp; <input type="submit" class="button" name="search" value="[[Find:raw]]" /> &nbsp;
		<a href="{page_path id='search'}">[[Advanced search]]</a>
    </div>
</form>
