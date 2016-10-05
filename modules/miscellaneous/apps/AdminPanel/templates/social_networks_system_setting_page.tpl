{foreach from=$socialNetworkSettings item='socialNetworkSetting'}
<div>
	{$socialNetworkSetting->getContent()}
</div>
{/foreach}
