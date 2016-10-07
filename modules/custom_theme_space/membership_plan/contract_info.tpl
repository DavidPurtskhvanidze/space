<div class="currentSubscriptionInfo">
    <h1 class="title">[[Your Current Subscription]]</h1>
    {$listing_add_url = {page_path id='listing_add'}}
    <div class="alert alert-info" role="alert">[[You have been subscribed to the Membership Plan below and now you can <a href="$listing_add_url">add listings</a>.]]</div>
    {assign var="contactFormPageUri" value=$GLOBALS.site_url|cat:"/contact/"}
    <h3>[[$contract.name]]</h3>
    <div class="well">
        <table class="table">
            <tbody class="table-hover">
            <tr class="info">
                <th>[[Membership Plan]]</th>
                <td>[[$contract.name]]</td>
            </tr>
                <tr>
                    <th>[[Expires]]</th>
                    <td>{if !empty($contract.expired_date)}{tr type='date'}{$contract.expired_date}{/tr}{else}[[Never]]{/if}</td>
                </tr>
                {if $is_auto_extension_available}
                    <tr class="autoExtension">
                        {if $contract.auto_extend}
                            <th>[[Auto-extension: enabled.]]</th>
                            <td><a href="{$GLOBALS.site_url}{$GLOBALS.current_page_uri}?action=change_auto_extend&amp;auto_extend=0">[[Disable auto-extension]]</a></td>
                        {else}
                            <th>[[Auto-extension: disabled.]]</th>
                            <td><a href="{$GLOBALS.site_url}{$GLOBALS.current_page_uri}?action=change_auto_extend&amp;auto_extend=1">[[Enable auto-extension]]</a></td>
                        {/if}
                    </tr>
                    <tr>
                        <td>([[If you enable this option and have enough funds on your credit balance, your subscription will be automatically extended.]])</td>
                    </tr>
                {/if}
                <tr>
                    <th>[[Price]]</th>
                    <td>{$contract.price}</td>
                </tr>
                <tr>
                    <th>[[Type]]</th>
                    <td>[[{$contract.type}]]</td>
                </tr>
                <tr>
                    <th>[[Max Number of Listings]]</th>
                    <td>{$contract.classifieds_listing_amount}</td>
                </tr>
                <tr>
                    <th>[[Description]]</th>
                    <td>[[$contract.description]]</td>
                </tr>
            </tbody>
        </table>
        {include file="packages.tpl" packages=$contract.packages}
    </div>
</div>
