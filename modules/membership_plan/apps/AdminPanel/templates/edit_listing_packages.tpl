<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li><a href="{$GLOBALS.site_url}/manage_listings/?action=restore&amp;searchId={$REQUEST.searchId}">[[Manage Listings]]</a></li>
		<li>[[Edit Packages]]</li>
	</ul>
</div>

<div class="page-content">
	<div class="page-header">
		<h1>[[Edit Listings Packages]]</h1>
	</div>
	<div class="row">
		{if !$actionCompleted}
			{display_error_messages}

			<div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>
			<form method="post" class="form form-horizontal">
				<input type="hidden" name="action" value="save_listing_packages">
				<input type="hidden" name="searchId" value="{$searchId}">
				{foreach from=$listingSIDs item="id"}
					<input type="hidden" name="listing_sids[]" value="{$id}">
				{/foreach}
				{foreach from=$formFields item=formField}
					<div class="form-group">
						<div class="control-label col-sm-3">
						[[$formField.caption]]
						{if $formField.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
						</div>
						<div class="col-sm-8">
							{if $formField.id == 'description'}
								{input property=$formField.id template='textarea.tpl'}
							{elseif $formField.id == 'video_allowed'}
								{input property=$formField.id template='radio_buttons.tpl'}
							{else}
								{input property=$formField.id}
							{/if}
						</div>
					</div>
				{/foreach}
				<div class="clearfix form-actions">
					<input type="submit" value="[[Save:raw]]" class="btn btn-default" />
				</div>
			</form>

			<div class="alert alert-warning">[[&quot;Disable&quot; the unwanted listing features. Specify new values of the Listing Package options to replace them in the package(s) of selected listing(s). Choose the option &quot;Leave intact&quot; for those fields which values you want to keep.]]</div>
		{else}
			<div class="alert alert-success">[[Packages of selected listings have been successfully updated]]</div>
			<a class="btn btn-link" href="{$GLOBALS.site_url}/manage_listings/?action=restore&searchId={$REQUEST.searchId}">[[Back to Manage Listings]]</a>
		{/if}
	</div>
</div>
