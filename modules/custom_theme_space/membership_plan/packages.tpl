<div class="packages">
    <h2>[[Available Packages]]</h2>
    <hr>
    {if $packages.value}
        {assign var="packages" value=$packages.value}
    {/if}
    {foreach from=$packages item=package}
        <div class="well">
            <h2 class="package-title">[[$package.name]]</h2>
            <table class="table table-hover">
                <tbody>
                    {foreach from=$package.packageDetails key="packageDetailId" item="packageDetail"}
                        {if $packageDetailId=='price'}
                            <tr class="{$packageDetailId}-group">
                                <th>Price</th>
                                <td class="fieldCaption {$packageDetailId}">{$GLOBALS.custom_settings.listing_currency}{$packageDetail.value}</td>
                            </tr>
                        {else}
                            <tr>
                                <th class="fieldCaption {$packageDetailId}">[[$packageDetail.caption]]</th>
                                <td class="fieldValue {$packageDetailId}">{$packageDetail.value}</td>
                            </tr>
                        {/if}
                    {/foreach}
                </tbody>
            </table>
        </div>
    {/foreach}

</div>
