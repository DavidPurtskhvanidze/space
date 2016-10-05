<div class="creditTransactions row">
	<h2>[[Credit Transactions]]</h2>

	<div class="balanceAndBuyMoreCreditsWrapper hint alert alert-warning">
		<div class="balance">
			[[Current balance of credits]] - <span class="fieldValueBalance">{$GLOBALS.current_user.balance}</span>.
		</div>
		<div class="buyMoreCredits">
			<a href="{page_path id='user_buy_credits'}">[[Buy more credits]]</a>
		</div>
	</div>

    <div class="creditTransactionForm row">
        <form method="get" action="" class="form-inline">

            <div class="form-group">
                <label for="sid">[[ID]]:</label>
                <input type="text" id="sid" name="sid" value="{$filtrationData.sid}" class="form-control">
            </div>

            <div class="form-group">

                {i18n->getDateFormat assign="date_format"}
                {capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}
                <div class="form-group formWithHelp">
                    <div class="form-group">
                        <label for="dateFrom">[[Period]]: [[from]]</label>
                        <input type="text" name="date_from" id="CTFid" value="{$filtrationData.date_from}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="dateToCTF">[[to]]</label>
                        <input type="text" id="dateToCTF" name="date_to" value="{$filtrationData.date_to}" class="form-control">
                    </div>
                    <span  class="help-block">[[date format: '$date_format', for example: '$date_format_example']]</span>
                </div>

            </div>

            <div class="form-group">

                <input type="hidden" name="credit_transactions_action" value="filter" />
                <input type="submit" class="btn btn-primary" value="[[Filter:raw]]" />

            </div>
        </form>
    </div>

	<div class="payments">
		<table class="table table-striped table-hover">
			<thead>
                <tr>
                    <th class="fieldCaptionId">[[ID]]</th>
                    <th class="fieldCaptionDate">[[Date]]</th>
                    <th class="fieldCaptionDescription">[[Description]]</th>
                    <th class="fieldCaptionAmount">[[Credits(+/-)]]</th>
                </tr>
			</thead>
            <tbody>
                {foreach from=$transactions item=transaction}
                    <tr class="{cycle values="oddrow,evenrow" advance=false}" onmouseover="this.className='highlightrow'" onmouseout="this.className='{cycle values="oddrow,evenrow"}'">
                        <td>{$transaction.sid}</td>
                        <td>{tr type="date"}{$transaction.date}{/tr}  {$transaction.date|date_format:"%H:%M:%S"}</td>
                        <td>
                            {if !empty($transaction.invoice_sid)}
                                {display_invoice_description invoice_sid=$transaction.invoice_sid payment_method='modules\credits\lib\PaymentMethodCredits'}
                            {elseif $transaction.action_id == 'ADD_CREDITS_BY_ADMIN'}
                                {assign var=comment value=$transaction.details.comment}
                                [[Credits added by administrator]] {if $comment}[[with comment '$comment']]{/if}
                            {elseif $transaction.action_id == 'SUBTRACT_CREDITS_BY_ADMIN'}
                                {assign var=comment value=$transaction.details.comment}
                                [[Credits subtracted by administrator]] {if $comment}[[with comment '$comment']]{/if}
                            {elseif $transaction.action_id == 'BUY_CREDITS'}
                                [[Credits purchased]]
                            {elseif $transaction.action_id == 'SUBSCRIPTION_EXTENDED'}
                                [[Subscription auto-extension]]
                            {elseif $transaction.action_id == 'INITIAL_BALANCE'}
                                [[Initial balance]]
                            {else}
                                {$transaction.action_id}
                            {/if}
                        </td>
                        <td align="right">{$transaction.amount}</td>
                    </tr>
                {/foreach}
            </tbody>
		</table>
	</div>
</div>
