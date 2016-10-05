<div class="quickSearchForm">
	<form id="quickSearchForm" action="{page_path id='search_results'}">
		<input type="hidden" name="action" value="search" />
        <div class="row">

            <div class="col-sm-3">
                {capture assign="placeholder"}[[FormFieldCaptions!Keywords]]{/capture}
                {search property=keywords id="keywords" placeholder=$placeholder template="string_with_autocomplete.tpl"}
                <div class="space-20 visible-xs"></div>
            </div>

            <div class="col-sm-3">
                {search property="category_sid" template="category_tree_noredirect.tpl"}
                <div class="space-20 visible-xs"></div>
            </div>

            <div class="col-sm-3">
                {search property="ZipCode" template="geo.distance.tpl"}
                <div class="space-20 visible-xs"></div>
            </div>

            <div class="col-sm-3">
                {capture assign="placeholder"}[[FormFieldCaptions!of Zip]]{/capture}
                {search property="ZipCode" placeholder=$placeholder template="geo.location.tpl"}
                <div class="space-20 visible-xs"></div>
            </div>
        </div>
        
        <div class="space-20"></div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <input type="submit" class="btn btn-orange btn1" value="[[Find:raw]]" />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <a class="h5" href="{page_path id='search'}">[[Advanced search]]</a>
            </div>
        </div>
	</form>
</div>
