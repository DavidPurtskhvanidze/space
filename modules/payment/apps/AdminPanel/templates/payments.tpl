<div class="page-content">
	<div class="alert alert-warning">
		[[The usernames marked with <i class="icon-remove-sign bigger-110 red"></i> belong to deleted users.]]
	</div>

	{include file="errors.tpl"}

	{display_error_messages}
	{display_warning_messages}
	{display_success_messages}
  <div class="col-xs-12">
    <div class="table-responsive">
      <div class="dataTables_wrapper">
        <div class="row">
          <div class="col-sm-12">
            {include file="miscellaneous^items_per_page_selector.tpl" search=$search}
          </div>
        </div>
        <table class="table table-striped table-hover dataTable">
          <thead>
            <tr>
              <th>
                <a href="?action=restore&sorting_fields[sid]={if $search.sorting_fields['sid'] == 'ASC'}DESC{else}ASC{/if}">[[ID]]</a>
                {if $search.sorting_fields['sid']}{if $search.sorting_fields['sid'] == 'ASC'}<i class="icon-caret-down"></i>{else}<i class="icon-caret-up"></i>{/if}{/if}
              </th>
              <th>
                <a href="?action=restore&sorting_fields[creation_date]={if $search.sorting_fields['creation_date'] == 'ASC'}DESC{else}ASC{/if}">[[Created]]</a>
                {if $search.sorting_fields['creation_date']}{if $search.sorting_fields['creation_date'] == 'ASC'}<i class="icon-caret-down"></i>{else}<i class="icon-caret-up"></i>{/if}{/if}
              </th>
              <th>
                <a href="?action=restore&sorting_fields[last_updated]={if $search.sorting_fields['last_updated'] == 'ASC'}DESC{else}ASC{/if}">[[Updated]]</a>
                {if $search.sorting_fields['last_updated']}{if $search.sorting_fields['last_updated'] == 'ASC'}<i class="icon-caret-down"></i>{else}<i class="icon-caret-up"></i>{/if}{/if}
              </th>
              <th>[[Description]]</th>
              <th>
                <a href="?action=restore&sorting_fields[username]={if $search.sorting_fields['username'] == 'ASC'}DESC{else}ASC{/if}">[[Username]]</a>
                {if $search.sorting_fields['username']}{if $search.sorting_fields['username'] == 'ASC'}<i class="icon-caret-down"></i>{else}<i class="icon-caret-up"></i>{/if}{/if}
              </th>
              <th>
                <a href="?action=restore&sorting_fields[amount]={if $search.sorting_fields['amount'] == 'ASC'}DESC{else}ASC{/if}">[[Amount]]</a>
                {if $search.sorting_fields['amount']}{if $search.sorting_fields['amount'] == 'ASC'}<i class="icon-caret-down"></i>{else}<i class="icon-caret-up"></i>{/if}{/if}
              </th>
              <th>
                <a href="?action=restore&sorting_fields[status]={if $search.sorting_fields['status'] == 'ASC'}DESC{else}ASC{/if}">[[Payment State]]</a>
                {if $search.sorting_fields['status']}{if $search.sorting_fields['status'] == 'ASC'}<i class="icon-caret-down"></i>{else}<i class="icon-caret-up"></i>{/if}{/if}
              </th>
              <th>[[Actions]]</th>
              <th>[[Gateway]]</th>
              <th class="viewCallbackData">[[Callback Data]]</th>
            </tr>
          </thead>
          <tbody>
          {assign var="total_price" value=0}
          {foreach from=$payments item=payment}
            {assign var="total_price" value=$total_price+$payment.amount.value}
              <tr data-payment-id="{$payment.id}">
                <td>{$payment.id}</td>
                <td>{tr type='date'}{$payment.creation_date}{/tr} {$payment.creation_date|date_format:"%H:%M:%S"}</td>
                <td>{$payment.last_updated}</td>
                <td>
                  {if $payment.invoice_sid.isEmpty}
                    [[$payment.description]]
                  {else}
                    {display_invoice_description invoice_sid=$payment.invoice_sid.value payment_method='modules\payment\lib\PaymentMethodMoney'}
                  {/if}
                </td>
                <td>
                  {if $payment.username.isNotEmpty}
                    <a href="{page_path id='edit_user'}?username={$payment.username}">{$payment.username}</a>
                  {else}
                    {$payment.deleted_user_username} <i class="icon-remove-sign bigger-110 red"></i>
                  {/if}
                </td>
                <td>{display_price_with_currency amount=$payment.amount.value payment_method='modules\payment\lib\PaymentMethodMoney'}</td>
                <td>
                  {if $payment.status=="Completed"}
                    {$label="success"}
                  {else}
                    {$label="warning"}
                  {/if}
                  <span class="label label-sm label-{$label} arrowed-in arrowed-in-right">
                    [[$payment.status]]
                  </span>

                </td>
                <td class="center align-middle">
                  {if $payment.status != 'Completed'}
                    <a class="btn btn-xs btn-success" href="?action=Endorse&payments[{$payment.id}]=1" title="[[Endorse]]">
                      <i class="icon-ok-sign bigger-130"></i>
                    </a>
	                <a class="itemControls delete btn btn-xs btn-danger" href="?action=Delete&payments[{$payment.id}]=1" onclick="return confirm('[[Are you sure you want to delete this payment?:raw]]')" title="[[Delete]]">
		              <i class="icon-trash bigger-120"></i>
	                </a>
                  {/if}
                </td>
                <td>{if $payment.exist}[[{$payment.payment_gateway_caption}]]{/if}</td>
                <td>{if strlen($payment.callback_data) > 0}&nbsp; <a class="viewCallbackData" href="?action=viewCallbackData&payment={$payment.id}">[[view]]</a>{/if}</td>
              </tr>
            {/foreach}
            <tr>
              <th colspan="5">[[Total Amount]]</th>
              <th colspan="5">{display_price_with_currency amount=$total_price payment_method='modules\payment\lib\PaymentMethodMoney'}</th>
            </tr>
          </tbody>
        </table>
        <div class="row">
          <div class="col-sm-6"></div>
          <div class="col-sm-6">
            <div class="dataTables_paginate">
              {include file="miscellaneous^page_selector.tpl" search=$search}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
