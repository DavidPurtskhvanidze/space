<div class="transactions-page">
    <div class="container">
        <h1 class="page-title">[[My Transactions]]</h1>

        {display_success_messages}
        {$url = "{page_path id='contact'}"}

        <div class="pendingTransactionMessage bg-info alert">[[Pending transactions are the payments that have not been completed automatically by the payment gateway due to some error and require administrator's endorsement. In certain cases, when you pay in cash or via money transfer, the website administrator needs more time to manually complete your payment. If you think that your pending transaction should have already been completed, please <a href="$url">contact the administrator</a>.]]</div>
    </div>
    <div class="bg-grey">
            <div class="container">
                <div class="row paymentsForm">
                    <form method="post" action="" role="form">
                        <input type="hidden" name="action" value="filter" />
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="" for="id">[[ID]]:</label>
                                    {search property='id'}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {search property='creation_date' template='date_inline.tpl'}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="status">[[Status]]</label>
                                    {search property='status'}
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="space-20"></div>
                            <input type="submit" class="btn btn-orange h5" value="&nbsp;&nbsp;[[Filter:raw]]&nbsp;&nbsp;" />
                            <div class="space-20"></div>
                            <div class="space-20"></div>
                        </div>
                    </form>
                </div>

                <div class="payments">
                    <div class="responsive-table">
                        <table class="table table-hover">
                            <thead class="cf">
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
                            </thead>

                            {assign var="total_price" value=0}
                            <tbody>
                            {foreach from=$payments item=payment}
                                {assign var="total_price" value=$total_price+$payment.amount.value}
                                <tr class="{cycle values="oddrow,evenrow"  advance=false}" onmouseover="this.className='highlightrow'" onmouseout="this.className='{cycle values="oddrow,evenrow"}'">
                                    <td data-title="[[ID]]" class="right">{$payment.id}</td>
                                    <td data-title="[[Date]]" class="right">{tr type='date'}{$payment.creation_date}{/tr} {$payment.creation_date|date_format:"%H:%M:%S"}</td>
                                    <td data-title="[[Description]]" class="right">
                                        {if $payment.invoice_sid.isEmpty}
                                            [[$payment.description]]
                                        {else}
                                            {display_invoice_description invoice_sid=$payment.invoice_sid.value payment_method='modules\payment\lib\PaymentMethodMoney'}
                                        {/if}
                                    </td>
                                    <td data-title="[[Money Paid]]" class="right">{display_price_with_currency amount=$payment.amount.value payment_method='modules\payment\lib\PaymentMethodMoney'}</td>
                                    <td data-title="[[Status]]" class="right paymentStatus {$payment.status|strtolower}">[[$payment.status]]</td>
                                    <td data-title="[[Action]]" class="right">{if $payment.isCompleted.isFalse && $payment.isInProgress.isFalse}<a href="?action=Complete&amp;paymentId={$payment.id}">[[Complete]]</a> | <a  onclick="return confirm('[[Are you sure you want to delete this transaction?:raw]]')" href="?action=Delete&amp;paymentId={$payment.id}">[[Delete]]</a>{/if}</td>
                                </tr>
                            {/foreach}
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="space-20"></div>
                                    <b>[[Total Amount]]:</b>&nbsp;&nbsp;&nbsp;<b class="orange">{display_price_with_currency amount=$total_price payment_method='modules\payment\lib\PaymentMethodMoney'}</b>
                                    <div class="space-20"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {extension_point name="modules\payment\apps\FrontEnd\ITransactionPage"}
        </div>

</div>
