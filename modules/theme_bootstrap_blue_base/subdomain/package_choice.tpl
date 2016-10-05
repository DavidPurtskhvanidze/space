{if !empty($packages)}
<div class="packageChoice">
    <h1 class="listingPackgeChoiceHeader page-title">[[Select a Package]]</h1>
    <div class="space-20"></div>
    <form method="post" action="">
        {CSRF_token}
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="row">
                    <input type="hidden" name="action" value="next" />
                    {foreach from=$packages item="package" name="packages"}
                        <div class="packageOption col-md-4 col-sm-6">
                            <div>
                                <label>
                                    <input type="radio" id="package_{$package.sid}" value="{$package.sid}" name="package_sid"/>
                                    <span class="packageName text-info">[[$package.name]]</span>
                                    <div class="packageDescription">
                                        <p>
                                            <span class="fieldCaption">[[Price]]&nbsp;{$GLOBALS.custom_settings.listing_currency}</span>
                                            <span class="fieldValue">{$package.price}</span>
                                        </p>
                                        <p>
                                            <span class="fieldCaption">[[SubDomain Lifetime(days)]]:</span>
                                            <span class="fieldValue">{$package.subdomain_lifetime}</span>
                                        </p>
                                        <p>[[$package.description]]</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    {/foreach}
                </div>
                <div>
                    <input type="submit" value="[[Next >>:raw]]" class="button btn btn-orange h6"/>
                </div>
            </div>
        </div>
    </form>
</div>
{else}
    {assign var="contactPageUrl" value={page_path id='contact'}}
    [[There are no subdomain packages available on your membership plan. Please <a href="$contactPageUrl">contact the administrator</a>]]
{/if}
<div class="space-20"></div>
