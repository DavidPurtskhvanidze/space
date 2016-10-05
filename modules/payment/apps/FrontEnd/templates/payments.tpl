<div class="transactionsPage">
	
	<h1>[[My Transactions]]</h1>
	
	{display_success_messages}
	{$url = "{page_path id='contact'}"}

    <div class="pendingTransactionMessage hint">[[Pending transactions are the payments that have not been completed automatically by the payment gateway due to some error and require administrator's endorsement. In certain cases, when you pay in cash or via money transfer, the website administrator needs more time to manually complete your payment. If you think that your pending transaction should have already been completed, please <a href="$url">contact the administrator</a>.]]</div>
	
	<form method="post" action="">
        {CSRF_token}
		<div class="paymentsForm">
			<span class="fieldCaption fieldCaptionId">[[ID]]:</span>
			<span class="fieldFormElement fieldFormElementId">{search property='id'}</span>
			<span class="fieldCaption fieldCaptionPeriodFrom">[[Period]]: [[from]]</span>
			<span class="fieldFormElement fieldFormElementCreationDate">{search property='creation_date'}</span>
			<span class="fieldFormElement fieldFormElementStatus">{search property='status'}</span>
			<input type="hidden" name="action" value="filter" />
			<span class="fieldFormElement fieldFormElementFilter"><input type="submit" value="[[Filter:raw]]" /></span>
		</div>
	</form>
	
	<div class="payments">
		<table>
			<tr>
				<th class="fieldCaptionId">
					<a href="?action=restore&amp;sorting_fields[id]={if $search.sorting_fields['id'] == 'ASC'}DESC{else}ASC{/if}">[[ID]]</a>
					{if $search.sorting_fields['id']}{if $search.sorting_fields['id'] == 'ASC'}<span class="sortDirectionArrow">&#9660;</span>{else}<span class="sortDirectionArrow">&#9650;</span>{/if}{/if}
				</th>
				<th class="fieldCaptionDate">
					<a href="?action=restore&amp;sorting_fields[creation_date]={if $search.sorting_fields['creation_date'] == 'ASC'}DESC{else}ASC{/if}">[[Date]]</a>
					{if $search.sorting_fields['creation_date']}{if $search.sorting_fields['creation_date'] == 'ASC'}<span class="sortDirectionArrow">&#9660;</span>{else}<span class="sortDirectionArrow">&#9650;</span>{/if}{/if}
				</th>
				<th class="fieldCaptionDescription">[[Description]]</th>
				<th class="fieldCaptionPrice">
					<a href="?action=restore&amp;sorting_fields[amount]={if $search.sorting_fields['amount'] == 'ASC'}DESC{else}ASC{/if}">[[Money Paid]]</a>
					{if $search.sorting_fields['amount']}{if $search.sorting_fields['amount'] == 'ASC'}<span class="sortDirectionArrow">&#9660;</span>{else}<span class="sortDirectionArrow">&#9650;</span>{/if}{/if}
				</th>
				<th class="fieldCaptionStatus">
					<a href="?action=restore&amp;sorting_fields[status]={if $search.sorting_fields['status'] == 'ASC'}DESC{else}ASC{/if}">[[Status]]</a>
					{if $search.sorting_fields['status']}{if $search.sorting_fields['status'] == 'ASC'}<span class="sortDirectionArrow">&#9660;</span>{else}<span class="sortDirectionArrow">&#9650;</span>{/if}{/if}
				</th>
				<th>[[Action]]</th>
			</tr>
			{assign var="total_price" value=0}
			{foreach from=$payments item=payment}
			{assign var="total_price" value=$total_price+$payment.amount.value}
			<tr class="{cycle values="oddrow,evenrow"  advance=false}" onmouseover="this.className='highlightrow'" onmouseout="this.className='{cycle values="oddrow,evenrow"}'">
				<td>{$payment.id}</td>
					<td>{tr type='date'}{$payment.creation_date}{/tr} {$payment.creation_date|date_format:"%H:%M:%S"}</td>
				<td>
					{if $payment.invoice_sid.isEmpty}
						[[$payment.description]]
					{else}
						{display_invoice_description invoice_sid=$payment.invoice_sid.value payment_method='modules\payment\lib\PaymentMethodMoney'}
					{/if}
				</td>
				<td>{display_price_with_currency amount=$payment.amount.value payment_method='modules\payment\lib\PaymentMethodMoney'}</td>
				<td class="paymentStatus {$payment.status|strtolower}">[[$payment.status]]</td>
				<td>{if $payment.isCompleted.isFalse && $payment.isInProgress.isFalse}<a href="?action=Complete&amp;paymentId={$payment.id}">[[Complete]]</a>| <a onclick="return confirm('[[Are you sure you want to delete this transaction?:raw]]')" href="?action=Delete&amp;paymentId={$payment.id}">[[Delete]]</a>{/if}</td>
			</tr>
			{/foreach}
			<tr>
                <td colspan="3"><b>[[Total Amount]]: </b></td>
                <td colspan="3"><b>{display_price_with_currency amount=$total_price payment_method='modules\payment\lib\PaymentMethodMoney'}</b></td>
			</tr>
		</table>
	</div>
	{extension_point name="modules\payment\apps\FrontEnd\ITransactionPage"}
</div>
