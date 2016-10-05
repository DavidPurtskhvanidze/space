<div class="quick-search-box">
	<form id="quickSearchForm" action="{page_path id='search_results'}" class="form-inline">
		<input type="hidden" name="action" value="search" />
		<div class="form-group">
			{capture assign="placeholder"}[[FormFieldCaptions!Keywords]]{/capture}
			{search property=keywords id="keywords" placeholder=$placeholder template="string_with_autocomplete.tpl"}
		</div>
		<div class="form-group">
            {search property="category_sid" template="category_tree_noredirect.tpl"}
        </div>

        <div class="form-group">
            {search property="ZipCode" template="geo.distance.tpl"}
        </div>

        <div class="form-group">
            {capture assign="placeholder"}[[FormFieldCaptions!of Zip]]{/capture}
            {search property="ZipCode" placeholder=$placeholder template="geo.location.tpl"}
        </div>

		<button type="submit" class="search-button wb">
			<span class="search-img"></span>
				<span class="search-button-text">
					Find
				</span>
		</button>
	</form>
</div>
