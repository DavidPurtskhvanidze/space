<span class="statCount">
	{if $pendingPaymentsCount != 0}
		<a class="count" href="{page_path module='payment' function='payments'}?action=search&status[equal]=Pending&creation_date[not_earlier]=">{$pendingPaymentsCount}</a>
		|
		<a class="amount" href="{page_path module='payment' function='payments'}?action=search&status[equal]=Pending&creation_date[not_earlier]=">
			<span class="currency">{$GLOBALS.custom_settings.transaction_currency}</span>
			<span class="value">{$pendingPaymentsAmount}</span>
		</a>
	{else}
		0
	{/if}
</span>
