<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li>[[Payments]]</li>
	</ul>
</div>
<div class="page-content PaymentsBlock">
	<div class="page-header">
		<h1>[[Payment Callback Data]]</h1>
	</div>
	<div class="row">
		<a href="javascript:history.go(-1)">Back</a>
		<table class="table">
			<tr class="{cycle values="even"}">
				<td>[[Payment Id]]:</td>
				<td>{$paymentSid}</td>
			</tr>
			{foreach from=$userFriendlyTransactionData key=caption item=value}
				<tr class="{cycle values="odd,even"}">
					<td>[[$caption]]:</td>
					<td>[[$value]]</td>
				</tr>
			{/foreach}
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr class="{cycle values="odd"}">
				<td>[[Callback data]]:</td>
				<td><pre>{$callbackData}</pre></td>
			</tr>
		</table>
	</div>
</div>




