<div class="availabelMembershipPlans">


    <h2>[[Other Membership Plans]]</h2>
    {$contactFormPageUri = "{page_path id='contact'}"}
    <div class="changeSubscriptionRequest message">[[To change your subscription before it expires please <a href="$contactFormPageUri">send a request</a> to the site administrator.]]</div>

    {foreach from=$membershipPlans item=membershipPlan}
        <div class="plan-block">
            <div class="plan-head">
                <h3>[[$membershipPlan.name]]</h3>
            </div>
            <div class="plan-details">
                <ul class="list-unstyled">
                    <li><span>[[Price]]:</span> {$membershipPlan.price}</li>
                    <li><span>[[Type]]:</span> [[$membershipPlan.type]]</li>
                    <li><span>[[Duration(days)]]:</span> {$membershipPlan.subscription_period}</li>
                    <li><span>[[Max Number of Listings]]:</span> {$membershipPlan.classifieds_listing_amount}</li>
                </ul>
            </div>
            <div class="plan-description">
                <p>[[$membershipPlan.description]]</p>
            </div>

            {include file="packages.tpl" packages=$membershipPlan.packages}
        </div>
    {/foreach}
</div>
