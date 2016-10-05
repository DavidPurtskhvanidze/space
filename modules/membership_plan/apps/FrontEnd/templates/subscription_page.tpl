<div class="subscriptionPage">
	<h1 class="subscriptionHeader">[[Choose a Subscription Plan]]</h1>
	<ul class="membershipPlanList">
		{foreach from=$availableMembershipPlans item="mp"}
			<li data-item-sid="{$mp.sid}">
				<h3>
					{$mp.name}
				</h3>

				<div class="description">
					{$mp.description}
				</div>
				<div class="priceSubscriptionPeriodWrapper">
					<div class="price">
						<span class="fieldCaption Price">[[Price]]:</span>
					<span class="fieldValue Price">
						{if $mp.price != '' && $mp.price != '0'}
							{display_price_with_currency amount=$mp.price.value}
						{else}
							[[Free of charge]]
						{/if}
					</span>
					</div>
					<div>
						<span class="fieldCaption SubscriptionPeriod">[[Subscription period]]:</span>
					<span class="fieldValue SubscriptionPeriod">
						{if !$mp.subscription_period.value} [[unlimited]] {else} [[$mp.subscription_period]] [[days]]{/if}
					</span>
					</div>
				</div>
				<div class="button">
					<form>
						<div>
							<input type="hidden" name="membershipPlanSID" value="{$mp.sid}">
							<input type="hidden" name="returnBackUri" value="{$returnBackUri}">
							<input type="submit" value="[[Subscribe Now:raw]]" class="button">
						</div>
					</form>
				</div>
			</li>
		{/foreach}
	</ul>
</div>
