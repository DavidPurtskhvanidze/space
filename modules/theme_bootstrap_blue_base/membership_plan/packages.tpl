<div class="packages">
    <div class="space-20"></div>
    <div class="bg-grey">
        <div class="space-20"></div>
        <div class="space-20"></div>
        <div class="container">
            <h5 class="h4 bordered">[[Available Packages]]</h5>
            <div class="space-20"></div>
            {if $packages.value}
                {assign var="packages" value=$packages.value}
            {/if}
            <div class="row">
                {foreach from=$packages item=package}
                    <div class="col-lg-4 col-md-6 col">
                        <div class="package-item">
                                <div><h5 class="h4">[[$package.name]]</h5></div>
                                {foreach from=$package.packageDetails key="packageDetailId" item="packageDetail"}
                                    {if $packageDetailId=='price'}
                                        <div class="orange field h3 {$packageDetailId}-group">
                                            <span class="fieldCaption {$packageDetailId}">{$GLOBALS.custom_settings.listing_currency}</span>
                                            <span class="fieldValue {$packageDetailId}">{$packageDetail.value}</span>
                                        </div>
                                    {else}
                                        <div class="col-md-12 col-sm-6">
                                            <div class="row field">
                                                <span class="col-xs-8 text-right fieldCaption {$packageDetailId}">[[$packageDetail.caption]]</span>
                                                <span class="col-xs-4 text-left fieldValue {$packageDetailId}">{$packageDetail.value}</span>
                                            </div>
                                        </div>
                                    {/if}
                                {/foreach}
                            <div class="clearfix"></div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
        <div class="space-20"></div>
        <div class="space-20"></div>
        <div class="space-20"></div>
    </div>
</div>
