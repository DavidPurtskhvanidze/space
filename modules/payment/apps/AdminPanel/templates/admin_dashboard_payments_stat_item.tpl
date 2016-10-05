<span class="statCount">
	{if $paymentsCount != 0}
		<a class="count" href="{page_path module='payment' function='payments'}?action=search&creation_date[not_earlier]=">{$paymentsCount}</a>
		|
		<a class="amount" href="{page_path module='payment' function='payments'}?action=search&creation_date[not_earlier]=">
			<span class="currency">{$GLOBALS.custom_settings.transaction_currency}</span>
			<span class="value">{$paymentsAmount}</span>
		</a>
	{else}
		0
	{/if}
</span>
