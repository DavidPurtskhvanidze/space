<div class="availabelMembershipPlans">
	<h2>[[Other Membership Plans]]</h2>
	{$contactFormPageUri = "{page_path id='contact'}"}
    <div class="changeSubscriptionRequest message">[[To change your subscription before it expires please <a href="$contactFormPageUri">send a request</a> to the site administrator.]]</div>
	{foreach from=$membershipPlans item=membershipPlan}
		<div class="item">
			<h3>[[$membershipPlan.name]]</h3>
			
			<div class="description">
				<p>[[$membershipPlan.description]]</p>
			</div>
			
			<div class="details">
				<ul>
					<li>[[Price]]: {$membershipPlan.price}</li>
					<li>[[Type]]: [[$membershipPlan.type]]</li>
					<li>[[Duration(days)]]:{if !$membershipPlan.subscription_period.value} [[unlimited]] {else} [[$membershipPlan.subscription_period]] {/if}</li>
					<li>[[Max Number of Listings]]: {$membershipPlan.classifieds_listing_amount}</li>
				</ul>
			</div>

			{include file="packages.tpl" packages=$membershipPlan.packages}
		</div>
	{/foreach}
</div>
