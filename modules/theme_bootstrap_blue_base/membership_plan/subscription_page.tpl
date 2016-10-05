<div class="subscriptionPage">
    <div class="container">
        <h1 class="page-title">[[Choose a Subscription Plan]]</h1>
    </div>
    <div class="space-20"></div>
    <div class="bg-grey">
        <div class="space-20"></div>
        <div class="space-20"></div>
        <div class="container">
            <div class="row">
                {foreach from=$availableMembershipPlans item="mp"}
                    <div class="col-sm-3 col-md-offset-2 subscription-plan">
                        <div class="header">
                            <div class="name">{$mp.name}</div>
                            <div class="price h5">
                                {if $mp.price != '' && $mp.price != '0'}
                                    {display_price_with_currency amount=$mp.price.value}
                                {else}
                                    [[Free of charge]]
                                {/if}
                            </div>
                            <div class="desc">
                                {$mp.description}
                            </div>
                        </div>
                        <div class="content">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <span>[[Subscription period]]:</span> {if !$mp.subscription_period.value} [[unlimited]] {else} [[$mp.subscription_period]] [[days]]{/if}
                                </li>
                                <li class="list-group-item">
                                    <span>[[Number of Listings]]:</span> {$mp.classifieds_listing_amount.value}
                                </li>
                            </ul>
                        </div>
                        <div class="footer">
                            <form>
                                <div>
                                    <input type="hidden" name="membershipPlanSID" value="{$mp.sid}">
                                    <input type="hidden" name="returnBackUri" value="{$returnBackUri}">
                                    <input type="submit" value="[[Subscribe Now:raw]]" class="btn btn-primary">
                                </div>
                            </form>
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
