<div class="page-content">
	<div class="info">
		<ul>
			<li>
				<div class="{if $log.numberOfImportedRecords > 0}text-success{/if}">[[Number of Imported Records]]: {$log.numberOfImportedRecords}</div>
			</li>
			<li>
				<div class="{if $log.numberOfInvalidRecords > 0}text-danger{/if}">[[Number of Invalid Records]]: {$log.numberOfInvalidRecords}</div>
			</li>
			{if $import_error_multilist_values_limit_exceeded}
				<li>
					<div class="text-danger">[[Import Error]]: [[The system cannot import more than 64 values in any given Multilist field.]]</div>
				</li>
			{/if}
		</ul>
	</div>
	{display_error_messages}
	{if $log.errors|@count}
		<table class="log import table table-striped table-bordered table-hover">
			<tr>
				<th class="line-number">[[Line #]]</th>
				<th>[[Errors / Warnings]]</th>
			</tr>
			{foreach from=$log.errors item=error}
				<tr>
					<td class="text-right">{$error.line}</td>
					<td>{$error.errorsText}</td>
				</tr>
			{/foreach}
		</table>
	{/if}
</div>
