<div class="userSearchPage">
	<h1>[[Find A Dealer]]</h1>
    {*<div class="row">*}
        <form class="user-search-form" role="form" method="get" action="{page_path id='users_search'}">
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        <div class="form-group col-md-4">
                            {capture assign='placeholder'}[[FormFieldCaptions!Dealership Name]]{/capture}
                            {search property="DealershipName" template="string_with_autocomplete.tpl" placeholder=$placeholder size="50"}
                        </div>
                        <div class="form-group col-md-3">
                            {capture assign='placeholder'}[[FormFieldCaptions!City]]{/capture}
                            {search property="City" placeholder=$placeholder}
                        </div>

                        <div class="form-group col-md-5">
                            {search property="State"}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="form-group col-md-6">
                            {search property="ZipCode" template="geo.distance.tpl"}
                        </div>

                        <div class="form-group col-md-3">
                            {capture assign='placeholder'}[[FormFieldCaptions!Of Zip]]{/capture}
                            {search property="ZipCode" template="geo.location.tpl"  placeholder=$placeholder}
                        </div>

                        <div class="form-group col-md-3">
                            <input type="hidden" name="action" value="search"/>
                            <button type="submit" class="btn btn-primary form-control">[[Search:raw]]</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    {*</div>*}
</div>
