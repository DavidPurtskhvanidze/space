{if $action == 'Deactivate'}
	{assign var='pageTitle' value='Deactivation'}
{elseif $action == 'Delete'}
	{assign var='pageTitle' value='Deletion'}
{elseif $action == 'Activate'}
	{assign var='pageTitle' value='Activation'}
{/if}

<div class="listingActionsPage">
	<div class="breadcrumbs">
		<ul class="breadcrumb">
			<li><a href="{$GLOBALS.site_url}/manage_listings/">[[Manage Listings]]</a></li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>[[$pageTitle]]</h1>
		</div>
		<div class="row">
		{display_success_messages}

		<p>[[Click]] <a href="{$GLOBALS.site_url}/manage_listings/">[[here]]</a> [[to go to Manage Listings.]]</p>
		</div>
	</div>
</div>
