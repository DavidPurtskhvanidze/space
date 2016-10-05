[[The value entered into]] '[[$fieldCaption]]' [[is out of allowed range (per field settings).]]
{if empty($maxValue)}
	[[The value should be more than $minValue.]]
{elseif empty($minValue)}
	[[The value should be less than $maxValue.]]
{else}
	[[The value should be within $minValue and $maxValue.]]
{/if}
