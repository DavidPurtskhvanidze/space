	<div class="info">
		<ul>
			<li><div class="{if $log.numberOfImportedRecords > 0}success{/if}">[[Number of Imported Records]]: {$log.numberOfImportedRecords}</div></li>
			<li><div class="{if $log.numberOfInvalidRecords > 0}error{/if}">[[Number of Invalid Records]]: {$log.numberOfInvalidRecords}</div></li>
		</ul>
	</div>
	{if $log.errors|@count}
		<table class="log">
			<tr>
				<th>[[Line #]]</th>
				<th>[[Errors / Warnings]]</th>
			</tr>
			{foreach from=$log.errors item=error}
				<tr>
					<td>{$error.line}</td>
					<td>{$error.errorsText}</td>
				</tr>
			{/foreach}
		</table>
	{/if}
</div>
