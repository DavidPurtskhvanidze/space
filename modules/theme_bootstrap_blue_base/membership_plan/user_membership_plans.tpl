<div class="availabelMembershipPlans">
    <div class="space-20"></div>
    <div class="space-20"></div>
    <div class="container">
        <h2 class="h3 bordered">[[Other Membership Plans]]</h2>
        <div class="space-20"></div>
        {$contactFormPageUri = "{page_path id='contact'}"}
        <div class="alert bg-info">[[To change your subscription before it expires please <a href="$contactFormPageUri">send a request</a> to the site administrator.]]</div>
    </div>

    {foreach from=$membershipPlans item=membershipPlan}
        <div class="plan-block">
            <div class="container">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="space-20"></div>
                    <div class="space-20"></div>
                    <div class="plan-head">
                        <h3 class="h4 text-center">[[$membershipPlan.name]]</h3>
                        <hr/>
                    </div>
                    <div class="plan-details">
                        <div class="row">
                            <div class="col-xs-6 text-right">[[Price]]:</div>
                            <div class="col-xs-6"><strong>{$membershipPlan.price}</strong></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 text-right">[[Type]]:</div>
                            <div class="col-xs-6"><strong>[[$membershipPlan.type]]</strong></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 text-right">[[Duration(days)]]:</div>
                            <div class="col-xs-6"><strong>{$membershipPlan.subscription_period}</strong></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 text-right">[[Max Number of Listings]]:</div>
                            <div class="col-xs-6"><strong>{$membershipPlan.price} {$membershipPlan.classifieds_listing_amount}</strong></div>
                        </div>
                    </div>
                </div>
                <div class="space-20 clearfix"></div>
                <div class="alert bg-info">
                    <p>[[$membershipPlan.description]]</p>
                </div>
            </div>
            {include file="packages.tpl" packages=$membershipPlan.packages}
        </div>
    {/foreach}
</div>
