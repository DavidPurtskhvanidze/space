{title}[[Business Catalog]]{if $current_company} :: {$current_company.name}{/if}{/title}
<div class="container">
	<h1 class="businessCatalogHeader title">{if $current_company}{$current_company.name}{else}[[Business Catalog]]{/if}</h1>

	{if $current_category}

		<div class="businessCatalog">
			{if $current_company}
				{$current_company.full}<hr /><a style="float:right" href="{page_path module='business_catalog' function='show_business_catalog'}?category_id={$current_category}">[[Back]]</a>
			{else}
				{foreach from=$records item=record}
					<div class="businessCatalogItem">
						<h3 class="businessCatalogProfileName">{$record.name}</h3>
						{if $record.description}
							{$record.description}<br />
						{/if}
						<dl class="captions businessCatalogCaptions">
							{if $record.location}
								<dt>[[Location]]:</dt>
								<dd>{$record.location}</dd>
							{/if}
							{if $record.email}
								<dt>[[E-mail]]:</dt>
								<dd>{$record.email}</dd>
							{/if}
							{if $record.url}
								<dt>[[Website]]:</dt>
								<dd><a href="{$record.url}">{$record.url}</a></dd>
							{/if}
							{if $record.address}
								<dt>[[Address]]:</dt>
								<dd>{$record.address}</dd>
							{/if}
							{if $record.phone}
								<dt>[[Phone]]:</dt>
								<dd>{$record.phone}</dd>
							{/if}
							{if $record.fax}
								<dt>[[Fax]]:</dt>
								<dd>{$record.fax}</dd>
							{/if}
						</dl>
						{if !($record.full == "" || $record.full=='<p>&nbsp;</p>')}
							<div class="businessCatalogProfileLink"><a href="{page_path module='business_catalog' function='show_business_catalog'}?category_id={$current_category}&amp;record_id={$record.id}">[[view full profile]]</a></div>
						{/if}
					</div>
				{/foreach}
			{/if}
		</div>

	{else}

		<br /><br />
		<table width="100%" cellpadding="10">
			{foreach from=$records item=record name=foreach}
				{if ($smarty.foreach.foreach.iteration - 1) % 2 == 0}
					<tr>
				{/if}
				<td>
					<a href="{page_path module='business_catalog' function='show_business_catalog'}?category_id={$record.id}">{$record.name}</a>
				</td>
				{if $smarty.foreach.foreach.iteration % 2 == 0}
					</tr>
				{/if}
			{/foreach}
			{if ($smarty.foreach.foreach.total - 1) % 2 != 0}
				<td></td>
				</tr>
			{/if}
		</table>
	{/if}
</div>

