<div class="paymentPage">
	<div class="paymentText">
		<h1 class="page-title">[[Select Payment Method]]</h1>
		<p>[[Dear]] {$GLOBALS.current_user.user_name}!</p>

		{display_price_with_currency amount=$payment.amount.value payment_method='modules\payment\lib\PaymentMethodMoney' assign='priceWithCurrency'}
		<div>
			[[Please make a payment in the amount of $priceWithCurrency for:]] {display_invoice_description invoice_sid=$payment.invoice_sid.value payment_method='modules\payment\lib\PaymentMethodMoney'}
		</div>

		<p>[[Please choose your preferred payment method among the following:]]</p>
	</div>
	<div class="paymentGateways">
	{foreach from=$gateways item=gateway}
		<form action="{$gateway.url}" method="post">
            {CSRF_token}
			{$gateway.hidden_fields}
			<input class="btn btn-default" type='submit' value='[[$gateway.caption]]' />
		</form>
	{/foreach}
	</div>
</div>
{extension_point name='modules\payment\apps\FrontEnd\IGatewaySelectionPageAdditionRenderer'}
