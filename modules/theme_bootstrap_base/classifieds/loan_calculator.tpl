<div class="loanCalculator">
	<script type="text/javascript" >
		var decimalSeparator = '{$decimalSeparator}';
		{literal}

		function validate(str)
		{
			regex = new RegExp('^[-+]?[0-9]+(\\'+decimalSeparator+'([0-9]+)?)?$');
			return regex.test(str);
		}

		function getValidatedAndNormalizedValueOrFalse(field, event, defaultValue)
		{
			if (field.value == '' && event.keyCode != 8 && event.keyCode != 46)
				field.value = defaultValue;
			if (field.value == '')
				return false;
			if (!(validate(field.value)))
			{
				field.style.background = "#FF6666";
				return false;
			}
			else
			{
				field.style.background = "white";
			}
			return field.value.replace(decimalSeparator, '.');
		}

		function calculate (obj, event)
		{
			var rate = getValidatedAndNormalizedValueOrFalse(obj.rate, event, 5);
			if (!rate) return;

			var term = getValidatedAndNormalizedValueOrFalse(obj.term, event, 12);
			if (!term) return;

			var type = obj.choice[0].value;
			if (obj.choice[1].checked)
				type = obj.choice[1].value;
			if (type == 'MP')
				calculateMP (obj, event, rate, term);
			else
				calculateLA (obj, event, rate, term);
		}

		function calculateMP (obj, event, rate, term)
		{
			var amount = getValidatedAndNormalizedValueOrFalse(obj.amount, event, 50000);
			if (!amount) return;

			rate = rate / 100;
			var payment = amount * (rate / 12) / (1 - Math.pow ((1 + rate/12), -term));
			payment = Math.round (100 * payment) / 100;
			obj.payment.value = payment.toString().replace('.', decimalSeparator);
		}

		function calculateLA (obj, event, rate, term)
		{
			var payment = getValidatedAndNormalizedValueOrFalse(obj.payment, event, 4280.37);
			if (!payment) return;

			rate = rate / 100;
			var amount = payment * (1 - Math.pow ((1 + rate/12), -term)) / (rate / 12);
			amount = Math.round (amount);
			obj.amount.value = amount.toString().replace('.', decimalSeparator);
		}
	{/literal}
	</script>
	<div class="alert alert-warning">[[Please use "$decimalSeparator" as the decimal separator.]]</div>
	<form  name="LoanCalculator" action="" >
		<table class="form table table-responsive">
			<tr>
				<td>
					<label><input type="radio" name="choice" value="MP" checked="checked" /> [[Monthly Payment]]</label>
				</td>
				<td>
					<label><input type="radio" name="choice" value="LA" /> [[Loan Amount]]</label>
				</td>
                <td>&nbsp;</td>
			</tr>
			<tr>
				<td>[[Term of Loan]]:</td>
				<td><input type="text" name="term" onkeyup="calculate(LoanCalculator, event)" class="form-control" /></td>
                <td>[[months]]</td>
			</tr>
			<tr>
				<td>[[Interest Rate]]:</td>
				<td><input type="text" name="rate" onkeyup="calculate(LoanCalculator, event)" class="form-control" /></td>
                <td>%</td>
			</tr>
			<tr>
				<td>[[Loan Amount]]:</td>
				<td><input type="text" name="amount" value="{$amount}" onfocus="LoanCalculator.choice[0].checked=true;LoanCalculator.choice[1].checked=false;" onkeyup="calculate(LoanCalculator, event)" class="form-control" /></td>
                <td>{$GLOBALS.custom_settings.listing_currency}</td>
			</tr>
			<tr>
				<td>[[Monthly Payment]]:</td>
				<td><input type="text" onfocus="LoanCalculator.choice[1].checked=true;LoanCalculator.choice[0].checked=false;" name="payment" onkeyup="calculate(LoanCalculator, event)" class="form-control" /></td>
                <td>{$GLOBALS.custom_settings.listing_currency}</td>
			</tr>
		</table>
	</form>
</div>
