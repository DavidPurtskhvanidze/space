<div class="container">
    <div class="transactionsPage">

        <h1 class="title">[[My Transactions]]</h1>

        {display_success_messages}
        {$url = "{page_path id='contact'}"}

        <div class="pendingTransactionMessage alert alert-warning">[[Pending transactions are the payments that have not been completed automatically by the payment gateway due to some error and require administrator's endorsement. In certain cases, when you pay in cash or via money transfer, the website administrator needs more time to manually complete your payment. If you think that your pending transaction should have already been completed, please <a href="$url">contact the administrator</a>.]]</div>

        <div class="row paymentsForm">
            <form method="post" action="" class="form-inline" role="form">

                <div class="form-group">
                    <label class="" for="id">[[ID]]:</label>
                    {search property='id'}
                </div>

                <div class="form-group">
                    {search property='creation_date' template='date_inline.tpl'}
                </div>
                <div class="form-group">
                    {search property='status'}
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="[[Filter:raw]]" />
                </div>
                <input type="hidden" name="action" value="filter" />

            </form>
        </div>

        <div class="payments">
            <table class="table table-striped table-hover">
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
                        <td>{if $payment.isCompleted.isFalse && $payment.isInProgress.isFalse}<a href="?action=Complete&amp;paymentId={$payment.id}">[[Complete]]</a> | <a  onclick="return confirm('[[Are you sure you want to delete this transaction?:raw]]')" href="?action=Delete&amp;paymentId={$payment.id}">[[Delete]]</a>{/if}</td>
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

</div>