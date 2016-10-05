{if $errors}
<p>[[The following errors occurred]]:</p>
    <p class="error">
	{foreach from=$errors key=error item=error_data}
	{if $error == 'NOT_IMPLEMENTED'}[[There is something missing in the code]]<br />
	{elseif $error == 'PRODUCT_PRICE_IS_NOT_SET'}[[No price is defined for this payment]]<br />
	{elseif $error == 'PRODUCT_NAME_IS_NOT_SET'}[[Product name is not defined]]<br />
	{elseif $error == 'PAYMENT_ID_IS_NOT_SET'}[[Callback parameters are missing required payment information.]]<br />
	{elseif $error == 'NONEXISTENT_PAYMENT_ID_SPECIFIED'}[[System is unable to identify the payment processed.]]<br />
	{elseif $error == 'PAYMENT_IS_NOT_PENDING'}[[The payment that you are requesting to process has already been processed before.]]<br />
	{elseif $error == 'INVALID_TRANSACTION'}[[It appears that there is something wrong with gateway settings.]]<br />
	{elseif $error == 'TRANSACTION_FAILED'}
		{$url = "{page_path id='user_payments'}"}
		[[Payment gateway has reported that the transaction has failed. You can see the transaction details on the <a href="$url">My Transactions</a> page, where you can try to complete the payment again.]]<br />
	{elseif $error == 'NO_INFORMATION_IS_YET_AVAILABLE'}[[Payment gateway has not yet provided payment information. Please follow the status information on "My Transactions" page.]]<br />
	{elseif $error == 'PAYPAL_PAYMENT_IS_STILL_PENDING'}
		[[The payment is still pending due to the following reason: $error_data]].<br />
		{assign var="link_to_documentation" value='<a href="https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/">https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/</a>'}
		[[Learn more about $error_data at $link_to_documentation]].<br />
		{if $error_data == 'unilateral'}
			[[unilateral: The payment is pending because it was made to an email address that is not yet registered or confirmed]] <br />
		{/if}
		[[Please contact administrator to complete you payment]].
	{elseif $error == 'CANCELED'}
		[[The payment was canceled]].
	{else}
		{$error}
	{/if}
	{/foreach}
    </p>
{/if}
