<div class="quickSearchForm">
	<form id="quickSearchForm" action="{page_path id='search_results'}" class="form-inline">
		<input type="hidden" name="action" value="search" />
		<div class="form-group input-group-sm">
			{capture assign="placeholder"}[[FormFieldCaptions!Keywords]]{/capture}
			{search property=keywords id="keywords" placeholder=$placeholder template="string_with_autocomplete.tpl"}
		</div>
		<div class="form-group input-group-sm">
            {search property="category_sid" template="category_tree_noredirect.tpl"}
        </div>

        <div class="form-group input-group-sm">
            {search property="ZipCode" template="geo.distance.tpl"}
        </div>

        <div class="form-group input-group-sm">
            {capture assign="placeholder"}[[FormFieldCaptions!of Zip]]{/capture}
            {search property="ZipCode" placeholder=$placeholder template="geo.location.tpl"}
        </div>

		<input type="submit" class="btn btn-primary btn-sm" value="[[Find:raw]]" />
		<a class="btn btn-link advancedSearchFormLink" href="{page_path id='search'}">[[Advanced search]]</a>
	</form>
</div>
