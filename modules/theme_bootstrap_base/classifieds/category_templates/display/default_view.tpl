{extension_point name='modules\main\apps\FrontEnd\IListingDisplayAdditionDisplayer'}

{title}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/title}
{keywords}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/keywords}
{description}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/description}


<div class="listing-details">
	<div class="row">
		<div class="col-sm-8">
			<h1>
				{if $listing.Sold.exists && $listing.Sold.isTrue}
					<span class="fieldValue fieldValueSold">[[SOLD]]!</span>
				{/if}
				{$listing}
                {if ($listing.Price.exists || $listing.Rent.exists) && (!empty($listing.Price.value) || !empty($listing.Rent.value))}
                    {strip}
                        <span class="orange fieldValue fieldValuePrice money">
                            <span class="currencySign">{$GLOBALS.custom_settings.listing_currency}</span>
                            {if $listing.Price.exists}
                                [[$listing.Price]]
                            {elseif $listing.Rent.exists}
                                [[$listing.Rent]]
                            {/if}
                        </span>
                    {/strip}
                {/if}
            </h1>
		</div>
		<div class="col-sm-4">
			<div class="pull-right">
				{include file="category_templates/display/listing_details_search_controls.tpl" listing=$listing}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8 listing-info">

			{display_success_messages}
			{if $messages}{include file="message.tpl"}{/if}

			{if $listing.pictures.numberOfItems > 0}
				{include file="listing_images.tpl" listing=$listing}
			{/if}

			<ul class="list-inline">
				{if $listing.pictures.numberOfItems > 0}
					<li>
						{module name="listing_feature_slideshow" function="display_slideshow" listing=$listing}
					</li>
				{/if}
				 {if $listing.Video.uploaded}
					<li>
						<a class="btn btn-primary" onclick='return openDialogWindow("[[Watch a video]]", this.href, 1087, true)'
                           href="{page_path id='video_player'}?listing_id={$listing.id}&raw_output=1">
							<span class="glyphicon glyphicon-facetime-video"></span> [[Watch a video]]
						</a>
					</li>
				{/if}
			</ul>

			<div class="panel-group">

				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">
							<a data-toggle="collapse" href="#summary">
								[[Summary]] <span class="pull-right"><span data-icon-down="glyphicon-chevron-up" data-icon-up="glyphicon-chevron-down" class="glyphicon glyphicon-chevron-up"></span></span>
							</a>
						</h2>
					</div>
					<div id="summary" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6">
									<dl class="dl-horizontal">
										{foreach $magicFields->excludeSystemFields()->excludeByType('text', 'rating', 'video', 'calendar', 'multilist') as $fieldId => $formField}
											<dt class="fieldCaption {$fieldId}">[[$formField.caption]]</dt>
											<dd class="fieldValue {$fieldId}">{display property=$fieldId}&nbsp;</dd>
										{/foreach}

										<dt class="fieldCaption views">[[FormFieldCaptions!Listing Views]]</dt>
										<dd class="fieldValue views">[[$listing.views]]&nbsp;</dd>

										<dt class="fieldCaption views">[[FormFieldCaptions!Date Posted]]</dt>
										<dd class="fieldValue views">[[$listing.activation_date]]&nbsp;</dd>

									</dl>
								</div>
								<div class="col-md-6">
									{module name='google_map' function='display_map' address=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State default_latitude=$listing.ZipCode.latitude default_longitude=$listing.ZipCode.longitude}
								</div>
							</div>
						</div>
					</div>
				</div>

				{foreach $magicFields->excludeSystemFields()->filterByType('text') as $fieldId => $formField}
					{if $listing.$fieldId.isNotEmpty}
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">
									<a data-toggle="collapse" href="#{$formField.caption|replace:' ':''}">
										[[$formField.caption]] <span class="pull-right"><span data-icon-down="glyphicon-chevron-up" data-icon-up="glyphicon-chevron-down" class="glyphicon glyphicon-chevron-up"></span></span>
									</a>
								</h3>
							</div>
							<div id="{$formField.caption|replace:' ':''}" class="panel-collapse collapse in">
								<div class="panel-body">
									<div class="fieldValue">{$listing.$fieldId}</div>
								</div>
							</div>
						</div>
					{/if}
				{/foreach}

				{foreach $magicFields->excludeSystemFields()->filterByType('multilist') as $fieldId => $formField}
					{if $listing.$fieldId.isNotEmpty}
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">
									<a data-toggle="collapse" href="#{$formField.caption|replace:' ':''}">
										[[$formField.caption]] <span class="pull-right"><span data-icon-down="glyphicon-chevron-up" data-icon-up="glyphicon-chevron-down" class="glyphicon glyphicon-chevron-up"></span></span>
									</a>
								</h3>
							</div>
							<div id="{$formField.caption|replace:' ':''}" class="panel-collapse collapse in">
								<div class="panel-body">
									<div class="fieldValue">{$listing.$fieldId}</div>
								</div>
							</div>
						</div>
					{/if}
				{/foreach}

				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<a data-toggle="collapse" href="#video">
								[[Video]] <span class="pull-right"><span data-icon-down="glyphicon-chevron-up" data-icon-up="glyphicon-chevron-down" class="glyphicon glyphicon-chevron-up"></span></span>
							</a>
						</h3>
					</div>
					<div id="video" class="panel-collapse collapse in">
						<div class="panel-body">
							{module name="listing_feature_youtube" function="display_youtube" listing=$listing listing=$listing width="380px" height="300px"}
						</div>
					</div>
				</div>

				{foreach $magicFields->filterByType('calendar') as $fieldId => $formField}
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">
								<a data-toggle="collapse" href="#{$formField.caption|replace:' ':''}">
									[[$formField.caption]] <span class="pull-right"><span data-icon-down="glyphicon-chevron-up" data-icon-up="glyphicon-chevron-down" class="glyphicon glyphicon-chevron-up"></span></span>
								</a>
							</h3>
						</div>
						<div id="{$formField.caption|replace:' ':''}" class="panel-collapse collapse in">
							<div class="panel-body">
								<div class="fieldValue">
									{display property=$fieldId}
								</div>
							</div>
						</div>
					</div>
				{/foreach}

			</div>

			{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
			{assign var='current_uri' value=$current_uri|urlencode}
			{module name="listing_comments" function="display_listing_details_comments" listing=$listing returnBackUri=$current_uri}
            {module name="facebook_comments" function="display_comments" url="{page_url id='listing'}"|cat:$listing.id}
		</div>

		<div class="col-sm-4">
			<div class="panel panel-default">
				<div class="panel-body">
					{include file="author_info.tpl" listing=$listing}
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<a data-toggle="collapse" href="#ManageSearch">
							<span data-icon-down="glyphicon-collapse-down" data-icon-up="glyphicon-expand" class="glyphicon glyphicon-expand"></span> [[Manage Search]]
						</a>
					</h3>
				</div>
				{include file="classifieds^search_controls.tpl"}
			</div>


			<div class="panel panel-default">
				<div class="list-group">
					<div class="list-group-item">
          	{include file="category_templates/display/social_network_buttons.tpl"}
					</div>
					<div class="list-group-item">
						<span class="fieldValue fieldValueListingRating">{display property=ListingRating template='rating_responsive.tpl'}</span>
					</div>
				</div>
			</div>

			{include file="category_templates/display/listing_details_listing_controls.tpl"}

		</div>
	</div>
	<script>
		$(function () {
			$('.collapse')
					.on('show.bs.collapse', function () {
						$('.glyphicon', $(this).parent())
								.addClass($('.glyphicon', $(this).parent()).data('icon-down'))
								.removeClass($('.glyphicon', $(this).parent()).data('icon-up'));
					})
					.on('hide.bs.collapse', function () {
						$('.glyphicon', $(this).parent())
								.addClass($('.glyphicon', $(this).parent()).data('icon-up'))
								.removeClass($('.glyphicon', $(this).parent()).data('icon-down'));
					})
		})
	</script>
</div>
