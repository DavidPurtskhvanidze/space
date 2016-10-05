<div class="clearBoth sellerListings">
	{if $user.DealershipName.exists && $user.DealershipName.isNotEmpty}
		{assign var=dealership_name value=$user.DealershipName}
		<h1>[[$dealership_name listings]]</h1>
	{elseif $user.username.exists && $user.username.isNotEmpty}
		{assign var=user_name value=$user.username}
		<h1>[[$user_name listings]]</h1>
	{/if}
</div>
{module name="classifieds" function="search_results" action="search" QUERY_STRING="username[equal]="|cat:$user.username}
