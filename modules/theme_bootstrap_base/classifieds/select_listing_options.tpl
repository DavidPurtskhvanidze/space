<div class="manageListingOptions">
	<h3>[[Manage Listing Options]]</h3>

	{if $alreadyActivatedFeatures}
		<div class="activatedFeatures">
			<p><strong>[[Already Activated:]]</strong></p>
			<ul class="list-unstyled">
			{foreach from=$alreadyActivatedFeatures item="feature"}
				<li>
					[[$feature.name]]
				</li>
				{foreachelse}
				<li>[[There is no option]]</li>
			{/foreach}
			</ul>
		</div>
	{/if}
	<form action="{page_path module='classifieds' function='manage_listing_options'}" method="post" class="respondInMainWindow">
		{if $availableFreeFeatures}
			<div class="availableFreeFeatures">
                <p><strong>[[Available Free Options:]]</strong></p>
				<ul class="list-unstyled">
				{foreach from=$availableFreeFeatures item="feature"}
					<li>
						<input type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" id="{$feature.id}"/>
						<label for="{$feature.id}">[[$feature.name]]</label>
					</li>
					{foreachelse}
					<li>[[There is no option]]</li>
				{/foreach}
				</ul>
			</div>
		{/if}
		{if $availablePaidFeatures}
			<div class="availablePaidFeatures">
                <p><strong>[[Available Paid Options:]]</strong></p>
				<ul class="list-unstyled">
				{foreach from=$availablePaidFeatures item="feature"}
					<li>
						<input type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" id="{$feature.id}"/>
						<label for="{$feature.id}">[[$feature.name]]</label> <span>{display_price_with_currency amount=$feature.price}</span>
					</li>
					{foreachelse}
					<li>[[There is no option]]</li>
				{/foreach}
				</ul>
			</div>
		{/if}
		{if $additionalListingOptions}
			<div class="additionalListingOption">
                <p><strong>[[Additional Options:]]</strong></p>
				<ul class="list-unstyled">
				{foreach from=$additionalListingOptions item="option"}
					<li>
						<input type="checkbox" name="selectedOptionIds[]" value="{$option.id}" id="{$option.id}"/>
						<label for="{$option.id}">[[{$option.caption}]]</label>
						{if $option.description}
							{*В $option.description должна быть уже переведенная фраза.*}
							<br/><span>{$option.description}</span>
						{/if}
						{if $option.additional_script}
							{$option.additional_script}
						{/if}
					</li>
				{/foreach}
				</ul>
			</div>
		{/if}
	{foreach from=$predefinedRequestData item="value" key="name"}
		<input type="hidden" name="{$name}" value="{$value}"/>
	{/foreach}
        {CSRF_token}
		<input type="hidden" name="listing_options_selected" value="1">
		<input type="hidden" name="searchId" value="{$smarty.request.searchId}">
		<input type="submit" class="btn btn-default pull-right" value="[[OK:raw]]"/>
		<input type="button" class="dialog-close" value="[[Close:raw]]" style="display:none"/>
	</form>
</div>

{if $listingIsActive}
	{require component="jquery" file="jquery.js"}
	<script type="text/javascript">
		$(document).ready(function(){
			$(".manageListingOptions form").bind('submit', function(e){
				var checked = $(".manageListingOptions input[name='selectedOptionIds[]']:checked").length;
				if (!checked) {
					e.preventDefault();
					e.stopImmediatePropagation();
					$('.manageListingOptions .dialog-close').trigger('click');
				}
				else
				{
                    $(".manageListingOptions form input[type='submit']").prop('disabled', true);
				}
			});
		});
	</script>
{/if}
