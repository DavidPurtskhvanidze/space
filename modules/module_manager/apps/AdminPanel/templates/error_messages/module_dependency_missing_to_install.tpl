[[The following modules cannot be installed:]]
<ul style="list-style-type: disc; padding-left: 20px;">
{foreach from=$failedModules item=failedModule}
	<li>{$failedModule.caption}</li>
{/foreach}
</ul>
[[In order to install selected modules listed above please first enable the following modules:]]
<ul style="list-style-type: disc; padding-left: 20px;">
{foreach from=$missingModules item=missingModule}
	<li>{$missingModule.caption} ({$missingModule.minVersion} - {$missingModule.maxVersion})</li>
{/foreach}
</ul>
