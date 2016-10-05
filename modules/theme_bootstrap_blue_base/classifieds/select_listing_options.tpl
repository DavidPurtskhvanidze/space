<div class="manageListingOptions">
	{if $alreadyActivatedFeatures}
		<div class="activatedFeatures">
			<div><strong>[[Already Activated:]]</strong></div>
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
			<p class="availableFreeFeatures">
                <div><strong>[[Available Free Options:]]</strong></div>
				<div>
				{foreach from=$availableFreeFeatures item="feature"}
					<div class="custom-form-control">
						<input type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" id="{$feature.id}"/>
						<label class="checkbox" for="{$feature.id}">[[$feature.name]]</label>
					</div>
					{foreachelse}
					<div class="custom-form-control">[[There is no option]]</div>
				{/foreach}
				</div>
			</p>
		{/if}
		{if $availablePaidFeatures}
			<p class="availablePaidFeatures">
                <div><strong>[[Available Paid Options:]]</strong></div>
				<div>
				{foreach from=$availablePaidFeatures item="feature"}
					<div class="custom-form-control">
						<input type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" id="{$feature.id}"/>
						<label class="checkbox" for="{$feature.id}">[[$feature.name]]</label> <span>{display_price_with_currency amount=$feature.price}</span>
					</div>
					{foreachelse}
					<div class="custom-form-control">[[There is no option]]</div>
				{/foreach}
				</div>
			</p>
		{/if}
		{if $additionalListingOptions}
			<p class="additionalListingOption">
                <div><strong>[[Additional Options:]]</strong></div>
				<div>
				{foreach from=$additionalListingOptions item="option"}
					<div class="custom-form-control">
						<input type="checkbox" name="selectedOptionIds[]" value="{$option.id}" id="{$option.id}"/>
						<label class="checkbox" for="{$option.id}">[[{$option.caption}]]</label>
						{if $option.description}
							{*В $option.description должна быть уже переведенная фраза.*}
							<br/><br/><span>{$option.description}</span>
						{/if}
						{if $option.additional_script}
							{$option.additional_script}
						{/if}
					</div>
				{/foreach}
				</div>
			</p>
		{/if}
	{foreach from=$predefinedRequestData item="value" key="name"}
		<input type="hidden" name="{$name}" value="{$value}"/>
	{/foreach}
        {CSRF_token}
		<input type="hidden" name="listing_options_selected" value="1">
		<input type="hidden" name="searchId" value="{$smarty.request.searchId}">
		<input type="submit" class="h6 btn btn-orange pull-right" value="[[OK:raw]]"/>
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
