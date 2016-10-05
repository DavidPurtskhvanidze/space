[[Cannot delete theme as it contains not writable files.]] [[Please make sure that the following files are writable:]]
<ul>
	{foreach from=$files item="file"}
		<li>{$file}</li>
	{/foreach}
</ul>
