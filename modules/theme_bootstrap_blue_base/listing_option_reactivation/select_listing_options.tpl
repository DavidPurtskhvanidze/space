<div class="manageListingOptions reactivation">
	<h1>[[Manage Reactivation Options]]</h1>

	<form action="" method="post">
		{if $activationPrice}
			<div class="requiredFeatures">
				<span class="activationOptionLabel">[[Listing Activation]]</span>
				<span class="activationOptionValue">{display_price_with_currency amount=$activationPrice}</span>
			</div>
		{/if}
		
		<div class="availableFreeFeatures">
			<h3>[[Available Free Options:]]</h3>
			<div>
			{foreach from=$availableFreeFeatures item="feature"}
				<div class="custom-form-control">
					{if in_array($feature.id, $selectedFeatures)}{$checked = ' checked="CHECKED" '}{else}{$checked = ''}{/if}
					<input type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" id="{$feature.id}" {$checked}/>
					<label class="checkbox" for="{$feature.id}">[[$feature.name]]</label>
				</div>
				{foreachelse}
				<li>[[There is no option]]</li>
			{/foreach}
			</div>
		</div>
		<div class="availablePaidFeatures">
			<h3>[[Available Paid Options:]]</h3>
			<div>
			{foreach from=$availablePaidFeatures item="feature"}
				<div class="custom-form-control">
					{if in_array($feature.id, $selectedFeatures)}{$checked = ' checked="CHECKED" '}{else}{$checked = ''}{/if}
					<input type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" id="{$feature.id}" {$checked}/>
					<label class="checkbox" for="{$feature.id}">[[$feature.name]]</label> <span>{display_price_with_currency amount=$feature.price}</span>
				</div>
				{foreachelse}
				<li>[[There is no option]]</li>
			{/foreach}
			</div>
		</div>
		<div class="featureTotals">
			<span class="totalPriceLabel">[[Total]] </span>
			<span class="totalPriceValue">{display_price_with_currency amount=0}</span>
		</div>
			
	{foreach from=$predefinedRequestData item="value" key="name"}
		<input type="hidden" name="{$name}" value="{$value}"/>
	{/foreach}
        {CSRF_token}
		<input type="hidden" name="action" value="save_options">
		<input class="h6 btn btn-orange" type="submit" value="[[Apply:raw]]"/>
	</form>


{require component="jquery" file="jquery.js"}
<script type="text/javascript">

	var numberOfDigitsAfterDecimal = {$numberOfDigitsAfterDecimal};
	var featurePrices = {
		{foreach from=$availablePaidFeatures item="feature"}
			'{$feature.id}':'{$feature.price}',
		{/foreach}
	};

	function calculateTotal() {
		var totalPrice = parseFloat({$activationPrice});
		$('input[name^="selectedOptionIds"]:checked').each(function(){
			if (undefined != featurePrices[$(this).val()]) {
				totalPrice += parseFloat(featurePrices[$(this).val()]);
			}
		});
		$('.totalPriceValue .value').html(formatPrice(totalPrice));
	}

	$(document).ready(function(){
		calculateTotal();
		$('input[name^="selectedOptionIds"]').change(function(){
			calculateTotal();
		});
	});

    function formatPrice(price) {
        if (numberOfDigitsAfterDecimal > 0) {
            return number_format(price, 2, "{i18n->getCurrentLanguageDecimalSeparator}","{i18n->getCurrentLanguageThousandsSeparator}");
        }
        else {
            return number_format(price, 0, "{i18n->getCurrentLanguageDecimalSeparator}","{i18n->getCurrentLanguageThousandsSeparator}");
        }
    }

    function number_format(number, decimals, dec_point, thousands_sep) {

        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
</div>
