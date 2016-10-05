<div class="quickSearchForm">
	<form method="GET" action="{page_path id='search_results'}">
		<fieldset>
			<div class="keywords">
				{search property=keywords template="quick_search_keywords.tpl"}
			</div>
			<div class="{$form_fields.category_sid.id}">
				<label for="{$form_fields.category_sid.id}">[[in]]</label>
				{search property=$form_fields.category_sid.id template="category_tree.tpl"}
			</div>
			<div class="formControls">
				<input type="hidden" name="action" value="search" />
				<input type="submit" name="search" value="[[Find:raw]]" />
				<span class="advancedSearchLink"><a href="{page_path id='search'}">[[Advanced search]]</a></span>
			</div>
		</fieldset>
	</form>
</div>
