<div class="packages">
	<h5 class="availablePackages">[[Available Packages]]</h5>

	{if $packages.value}
		{assign var="packages" value=$packages.value}
	{/if}
    <div class="row">
        {foreach from=$packages item=package}
            <div class="col-xs-4 col col-{$packages|@count}">
                <ul class="package-item">
                    <li><h5>[[$package.name]]</h5></li>
                    {foreach from=$package.packageDetails key="packageDetailId" item="packageDetail"}
                        {if $packageDetailId=='price'}
                            <li class="{$packageDetailId}-group">
                                <span class="fieldCaption {$packageDetailId}">{$GLOBALS.custom_settings.listing_currency}</span>
                                <span class="fieldValue {$packageDetailId}">{$packageDetail.value}</span>
                            </li>
                        {else}
                            <li>
                                <span class="fieldCaption {$packageDetailId}">[[$packageDetail.caption]]</span>
                                <span class="fieldValue {$packageDetailId}">{$packageDetail.value}</span>
                            </li>
                        {/if}
                    {/foreach}
                </ul>
            </div>
        {/foreach}
    </div>
</div>
