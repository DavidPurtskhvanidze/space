<div class="quickSearchForm">
	<form id="quickSearchForm" action="{page_path id='search_results'}" class="form-inline">
        <div class="row">
                <div class="form-group input-group-md">
                    {search property="ZipCode" template="geo.location.tpl" placeholder="[[FormFieldCaptions!of Zip]]"}
                </div>
                <div class="form-group input-group-md">
                    {search property="keywords" template="string_with_autocomplete.tpl" placeholder="[[FormFieldCaptions!Keywords]]"}
                </div>

                <input type="hidden" name="action" value="search" />
        </div>
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
        <input type="hidden" name="category_sid[tree][]" value="{$category_sid}" />
	</form>
</div>
