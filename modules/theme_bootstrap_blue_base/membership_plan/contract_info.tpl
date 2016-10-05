<div class="container">
	<h1 class="page-title">[[Your Current Subscription]]</h1>
    <div class="space-20"></div>
    <div class="space-20"></div>
    {$listing_add_url = {page_path id='listing_add'}}
    <div class="message alert bg-info">[[You have been subscribed to the Membership Plan below and now you can <a href="$listing_add_url">add listings</a>.]]</div>
	{assign var="contactFormPageUri" value=$GLOBALS.site_url|cat:"/contact/"}
    <div class="space-20"></div>
	<div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="row">
                <div class="col-xs-6 text-right">
                    <h3 class="h4">[[Expires]]:</h3>
                </div>
                <div class="col-xs-6">
                    <h3 class="h4 orange">
                        {if !empty($contract.expired_date)}{tr type='date'}{$contract.expired_date}{/tr}{else}[[Never]]{/if}
                    </h3>
                </div>
                {if $is_auto_extension_available}
                    <div class="col-sm-12 text-center">
                        {if $contract.auto_extend}
                            <span class="state">[[Auto-extension: enabled.]]</span>
                            <a href="{$GLOBALS.site_url}{$GLOBALS.current_page_uri}?action=change_auto_extend&amp;auto_extend=0">[[Disable auto-extension]]</a>
                        {else}
                            <span class="state">[[Auto-extension: disabled.]]</span>
                            <a href="{$GLOBALS.site_url}{$GLOBALS.current_page_uri}?action=change_auto_extend&amp;auto_extend=1">[[Enable auto-extension]]</a>
                        {/if}
                        <p class="hint">([[If you enable this option and have enough funds on your credit balance, your subscription will be automatically extended.]])</p>
                    </div>
                {/if}
            </div>
            <div class="row">
                <div class="col-xs-6 text-right">
                    <h3 class="h4">[[Membership Plan]]:</h3>
                </div>
                <div class="col-xs-6">
                    <h3 class="h4 orange">"[[$contract.name]]"</h3>
                </div>
            </div>
            <hr/>
            <div class="details">
                <div class="row">
                    <div class="col-xs-6 text-right">[[Price]]:</div>
                    <div class="col-xs-6"><strong>{$contract.price}</strong></div>
                </div>
                <div class="row">
                    <div class="col-xs-6 text-right">[[Type]]:</div>
                    <div class="col-xs-6"><strong>[[{$contract.type}]]</strong></div>
                </div>
                <div class="row">
                    <div class="col-xs-6 text-right">[[Max Number of Listings]]:</div>
                    <div class="col-xs-6"><strong>{$contract.classifieds_listing_amount}</strong></div>
                </div>
            </div>
            <div class="space-20"></div>
            <div class="space-20"></div>
        </div>
    </div>

    <div class="alert bg-info">
        <p>[[$contract.description]] </p>
    </div>
</div>

{include file="packages.tpl" packages=$contract.packages}
