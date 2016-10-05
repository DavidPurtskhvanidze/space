[[The following modules cannot be enabled:]]
<ul style="list-style-type: disc; padding-left: 20px;">
{foreach from=$failedModules item=failedModule}
	<li>{$failedModule.caption}</li>
{/foreach}
</ul>
[[In order to enable selected modules listed above please first enable the following modules:]]
<ul style="list-style-type: disc; padding-left: 20px;">
{foreach from=$missingModules item=missingModule}
	<li>{$missingModule.caption} ({$missingModule.minVersion} - {$missingModule.maxVersion})</li>
{/foreach}
</ul>

<div class="dependencyAction">
	<p>[[Would you like to enable all modules listed above?]]</p>
	<form>
		<input type="hidden" name="action" value="enable" />
		{foreach from=$failedModules item=failedModule}
			<input type="hidden" name="modules[]" value="{$failedModule.name}" />
		{/foreach}
		{foreach from=$missingModules item=missingModule}
			<input type="hidden" name="modules[]" value="{$missingModule.name}" />
		{/foreach}
		<input type="submit" value="[[Enable:raw]]" class="btn btn-default">
	</form>
	<form method="get">
		<input type="hidden" name="restore" value="1" />
		<input type="submit" value="[[Cancel:raw]]" class="btn btn-default">
	</form>
</div>
