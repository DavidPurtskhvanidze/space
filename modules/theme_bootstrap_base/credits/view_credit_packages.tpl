<div class="transactionsPage">
	<div class="payments">
		<table class="table">
            <thead>
                <tr>
                    <th class="font-normal">[[Credit Package Name]]</th>
                    <th class="font-normal">[[Credits Number In Package]]</th>
                    <th class="font-normal">[[Package Price]]</th>
                    <th class="font-normal">[[Price Per Credit]]</th>
                    <th class="font-normal">[[Credits Amount]]</th>
                    <th class="font-normal">[[Total Cost]]</th>
                </tr>
            </thead>

		{$total = 0}
        <tbody>
            {foreach from=$packagesInfo item=packageInfo}
                <tr class="{cycle values="oddrow,evenrow"}">
                    <td>{$packageInfo.name}</td>
                    <td>{$packageInfo.credits_number}</td>
                    <td><span class="currencySign">{$GLOBALS.custom_settings.transaction_currency}</span>{$packageInfo.price}</td>
                    <td><span class="currencySign">{$GLOBALS.custom_settings.transaction_currency}</span>{$packageInfo.price_per_credit}</td>
                    <td>{$packageInfo.amount}</td>
                    <td><span class="currencySign">{$GLOBALS.custom_settings.transaction_currency}</span>{$packageInfo.total}</td>
                </tr>
                {$total=$total+$packageInfo.total}
            {/foreach}
            <tr>
                <td colspan="6">[[Total:]]<span class="currencySign">{$GLOBALS.custom_settings.transaction_currency}</span>{$total}</td>
            </tr>
        </tbody>

		</table>
	</div>
</div>
