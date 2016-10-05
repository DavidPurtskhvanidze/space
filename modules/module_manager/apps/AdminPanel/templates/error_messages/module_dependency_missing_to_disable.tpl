[[The following modules cannot be disabled:]]
<ul style="list-style-type: disc; padding-left: 20px;">
	{foreach from=$failedModules item=failedModule}
		<li>{$failedModule.caption}</li>
	{/foreach}
</ul>
[[In order to disable selected modules listed above please first disable the following modules:]]
<ul style="list-style-type: disc; padding-left: 20px;">
	{foreach from=$yetEnabledModules item=yetEnabledModule}
		<li>{$yetEnabledModule.caption}</li>
	{/foreach}
</ul>

<div class="dependencyAction">
	<p>[[Would you like to disable all modules listed above?]]</p>
	<form>
		<input type="hidden" name="action" value="disable" />
		{foreach from=$failedModules item=failedModule}
			<input type="hidden" name="modules[]" value="{$failedModule.name}" />
		{/foreach}
		{foreach from=$yetEnabledModules item=yetEnabledModule}
			<input type="hidden" name="modules[]" value="{$yetEnabledModule.name}" />
		{/foreach}
		<input type="submit" value="[[Disable:raw]]" class="btn btn-default">
	</form>
	<form method="get">
		<input type="hidden" name="restore" value="1" />
		<input type="submit" value="[[Cancel:raw]]" class="btn btn-default">
	</form>
</div>
