<div class="container">
    <div class="userSearchPage">
        <h1 class="page-title">[[Find A Dealer]]</h1>
        <form class="user-search-form" role="form" method="get" action="{page_path id='users_search'}">
            <div class="row">
                <div class="form-group col-md-4">
                    {capture assign='placeholder'}[[FormFieldCaptions!Dealership Name]]{/capture}
                    <label for="DealershipName">{$placeholder}</label>
                    {search property="DealershipName" template="string_with_autocomplete.tpl" size="60"}
                </div>
                <div class="form-group col-md-4">
                    {capture assign='placeholder'}[[FormFieldCaptions!City]]{/capture}
                    <label for="City">{$placeholder}</label>
                    {search property="City"}
                </div>

                <div class="form-group col-md-4">
                    <label for="State">[[FormFieldCaptions!State]]</label>
                    {search property="State"}
                </div>

                <div class="form-group col-md-4">
                    <label for="ZipCode">[[FormFieldCaptions!Search Within]]</label>
                    {search property="ZipCode" template="geo.distance.tpl"}
                </div>

                <div class="form-group col-md-4">
                    {capture assign='placeholder'}[[FormFieldCaptions!Of Zip]]{/capture}
                    <label for="ZipCode">{$placeholder}</label>
                    {search property="ZipCode" template="geo.location.tpl"}
                </div>
            </div>

            <hr class="hidden-xs hidden-sm"/>

            <div class="form-group text-center">
                <div class="space-20"></div>
                <input type="hidden" name="action" value="search"/>
                <button type="submit" class="btn btn-orange btn1">[[Search:raw]]</button>
            </div>
        </form>
    </div>
</div>
<div class="space-20"></div>

