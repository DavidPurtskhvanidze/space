<span class="statCount">
	{if $endorsedPaymentsCount != 0}
		<a class="count" href="{page_path module='payment' function='payments'}?action=search&status[equal]=Completed&creation_date[not_earlier]=">{$endorsedPaymentsCount}</a>
		|
		<a class="amount" href="{page_path module='payment' function='payments'}?action=search&status[equal]=Completed&creation_date[not_earlier]=">
			<span class="currency">{$GLOBALS.custom_settings.transaction_currency}</span>
			<span class="value">{$endorsedPaymentsAmount}</span>
		</a>
	{else}
		0
	{/if}
</span>
