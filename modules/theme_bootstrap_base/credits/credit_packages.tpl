<div class="creditPackagesPage">
	<h1 class="creditPackagesHeader">[[Credit Packages]]</h1>

	{include file='errors.tpl'}
	{if $credit_packages|is_array && $credit_packages|@count > 0}
		<form action="" method="post">
            {CSRF_token}
			<table class="table table-responsive table-no-border">
                <thead class="table-header">
                    <tr>
                        <th>[[Name]]</th>
                        <th>[[Description]]</th>
                        <th>[[Credits Per Package]]</th>
                        <th>[[Package Price]]</th>
                        <th>[[Package Qty]]</th>
                        <th>[[Total Credits]]</th>
                        {assign var="transaction_currency" value=$GLOBALS.custom_settings.transaction_currency}
                        <th>[[Total Cost, $transaction_currency]]</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$credit_packages item=credit_package}
                        <tr class="{cycle values="oddrow,evenrow"  advance=false}" onmouseover="this.className='highlightrow'" onmouseout="this.className='{cycle values="oddrow,evenrow"}'">
                            <td>
                                {$credit_package.name}
                            </td>
                            <td>
                                {$credit_package.description}
                            </td>
                            <td id="credits_item_{$credit_package.sid}">
                                {$credit_package.credits_number}
                            </td>
                            <td id="price_item_{$credit_package.sid}">
                                {$credit_package.price}
                            </td>
                            <td>
                                {assign var=sid value=$credit_package.sid}
                                <input type="text" class="quantity form-control" name="quantities[{$credit_package.sid}]" size="4" value="{$REQUEST.quantities.$sid|default:0}">
                            </td>
                            <td id="total_credits_{$credit_package.sid}"></td>
                            <td id="total_cost_{$credit_package.sid}"></td>
                        </tr>
                    {/foreach}

                    <tr>
                        <td colspan="4">&nbsp;</td>
                        <td>[[Total]]:</td>
                        <td class="fieldValueGrandTotalCredits" id="GrandTotalCredits"></td>
                        <td class="fieldValueGrandTotalPrice" id="GrandTotalPrice"></td>
                    </tr>

                    <tr>
                        <td colspan="4">&nbsp;</td>
                        <td>[[Cost per credit]]:</td>
                        <td class="fieldValueCostPerCredit" id="CostPerCredit"></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="7">
                            <input type="hidden" name="action" value="buyCreditPackages" />
                            <input type="submit" class="btn btn-default" value="[[Buy:raw]]" />
                        </td>
                    </tr>

                </tbody>
			</table>
		</form>
		{require component="jquery" file="jquery.js"}
		{require component="jquery-calculation" file="jquery.calculation.js"}

		<script type="text/javascript">
			{literal}
			$(document).ready(
			function (){
				$("input[name^=quantities]").bind("keyup", recalc);
				recalc();
			}
			);

			function recalc(){
			$("[id^=total_cost]").calc(
				// the equation to use for the calculation
				"qty * price",
				// define the variables used in the equation, these can be a jQuery object
				{
					qty: $("input[name^=quantities]"),
					price: $("[id^=price_item_]")
				},
				// define the formatting callback, the results of the calculation are passed to this function
				function (s){
					// return the number as a dollar amount
					return s.toFixed(2);
				},
				// define the finish callback, this runs after the calculation has been complete
				function ($this){
					// sum the total of the $("[id^=total_cost]") selector
					var sum = $this.sum();
					$("#GrandTotalPrice").text(
						// round the results to 2 digits
						sum.toFixed(2)
					);
				}
			);
			$("[id^=total_credits]").calc(
				"qty * price",
				{
					qty: $("input[name^=quantities]"),
					price: $("[id^=credits_item_]")
				},
				// define the formatting callback, the results of the calculation are passed to this function
				function (s){
					return s.toFixed(0);
				},
				// define the finish callback, this runs after the calculation has been complete
				function ($this){
					// sum the total of the $("[id^=total_credits]") selector
					var sum = $this.sum();
					$("#GrandTotalCredits").text(
						sum.toFixed(0)
					);
				}
			);
			var grandTotalPrice = parseFloat($("#GrandTotalPrice").text());
			var grandTotalCredits = parseFloat($("#GrandTotalCredits").text());
			var costPerCredit = 0;
			if (grandTotalCredits > 0)
			{
				var costPerCredit = grandTotalPrice / grandTotalCredits;
			}
			$("#CostPerCredit").text(costPerCredit.toFixed(2));
			}
		{/literal}
		</script>
	{elseif $credit_packages|is_array}
		{assign var="contactPageUrl" value=$GLOBALS.site_url|cat:"/contact/"}
		<p class="error">[[Credit packages have not been created yet. Please <a href="$contactPageUrl">contact the administrator</a>.]]</p>
	{/if}
</div>
