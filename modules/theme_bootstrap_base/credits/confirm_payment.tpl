<div class="paymentConfirmationPage">
	<h1>[[Payment Confirmation]]</h1>
	<p class="notification">[[Please check the information below and click 'Confirm to proceed'.]]</p>
	<div class="paymentText">
		<table>
			<tr>
				<td>[[Description]]:</td>
				<td>
					{display_invoice_description invoice_sid=$invoice_sid payment_method='modules\credits\lib\PaymentMethodCredits'}
				</td>
			</tr>
			<tr>
				<td>[[Cost]]:</td>
				<td>{display_price_with_currency amount=$amount payment_method='modules\credits\lib\PaymentMethodCredits'}</td>
			</tr>
			<tr>
				<td>[[Your balance]]:</td>
				<td>{display_price_with_currency amount=$GLOBALS.current_user.balance payment_method='modules\credits\lib\PaymentMethodCredits'}</td>
			</tr>
		</table>
	</div>
	<form action="">
		<div class="confirmPaymentForm">
			<input type="hidden" name="invoice_sid" value="{$invoice_sid}"/>
			<input type="hidden" name="action" value="confirm"/>
			<input type="submit" value="[[Confirm:raw]]" class="btn btn-default"/>
		</div>
	</form>
</div>
