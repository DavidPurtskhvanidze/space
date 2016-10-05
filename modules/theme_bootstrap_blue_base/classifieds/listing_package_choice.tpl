<div class="listingPackageChoice">
    <div class="container">
        <h1 class="listingPackgeChoiceHeader page-title">[[Select a Package]]</h1>
    </div>
    <div class="space-20"></div>
    <div class="container">
        <form method="post" action="">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        {foreach from=$listing_packages item="listing_package" name="listing_packages"}
                            <div class="packageOption col-md-4 col-sm-6">
                                <label>
                                    <input type="radio" value="{$listing_package.sid}" name="listing_package_sid"/>
                                    <span class="packageName text-info">[[$listing_package.name]]</span>
                                    <div class="packageDescription">[[$listing_package.description]]</div>
                                </label>
                            </div>
                        {/foreach}
                    </div>
                    <div>
                        <input type="submit" value="[[Next >>:raw]]" class="button btn btn-default btn-orange h6"/>
                        <input type="hidden" name="category_id" value="{$REQUEST.category_id|default:''}"/>
                        {CSRF_token}
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
