<div class="packages">
	<h4>[[Available Packages]]</h4>

	{if $packages.value}
		{assign var="packages" value=$packages.value}
	{/if}
	{foreach from=$packages item=package}
		<div class="item">
			<div class="nameAndDescriptionWrapper">
				<h4>[[$package.name]]</h4>
				<p>[[$package.description]]</p>
			</div>
			<div class="details">
				<table>
					{foreach from=$package.packageDetails key="packageDetailId" item="packageDetail"}
						<tr>
							<td><span class="fieldCaption {$packageDetailId}">[[$packageDetail.caption]]</span></td>
							<td><span class="fieldValue {$packageDetailId}">{$packageDetail.value}</span></td>
						</tr>
					{/foreach}
				</table>
			</div>
		</div>
	{/foreach}
</div>
