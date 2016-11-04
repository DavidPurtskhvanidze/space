<div class="container">
    <section class="user-search-form-block">
        <h1 class="title">[[Find A Dealer]]</h1>
        <form class="user-search-form" role="form" method="get" action="{page_path id='users_search'}">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        {capture assign='placeholder'}[[FormFieldCaptions!Dealership Name]]{/capture}
                        {search property="DealershipName" template="string_with_autocomplete.tpl" placeholder=$placeholder size="50"}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">City</label>
                        {capture assign='placeholder'}[[FormFieldCaptions!City]]{/capture}
                        {search property="City" placeholder=$placeholder}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">State</label>
                        {search property="State"}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Distance</label>
                        {search property="ZipCode" template="geo.distance.tpl"}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Zip Code</label>
                        {capture assign='placeholder'}[[FormFieldCaptions!Of Zip]]{/capture}
                        {search property="ZipCode" template="geo.location.tpl"  placeholder=$placeholder}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <input type="hidden" name="action" value="search"/>
                        <button type="submit" class="default-button form-control wb">[[Search:raw]]</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

