{require component="js" file="toggleListingAction.js"}
<div class="listing-controls pull-right" data-listingId="{$listing.id}">
	<ul class="list-inline">
		<li>
			<label>
				<input type="checkbox" name="listings[{$listing.id}]" value="1" class="item-selector" />
				<span class="checked glyphicon glyphicon-check" title="[[Selected:raw]]" data-toggle="tooltip" date-placement="top"></span>
				<span class="unchecked glyphicon glyphicon-unchecked" title="[[Select:raw]]" data-toggle="tooltip" date-placement="top"></span>
			</label>
		</li>
		<li>
			<a href="?action=delete&amp;listings[{$listing.id}]=1" title="[[Delete:raw]]">
				<span class="glyphicon glyphicon-remove"></span>
			</a>
		</li>
		<li>
			<label>
				<input title="[[Compare Ad:raw]]" type="checkbox" name="compareAddSwitch" onchange="toggleListingAction(this, '{page_path id='listing_compare'}', '{page_path module='classifieds' function='remove_from_comparison'}')" value="{$listing.id}"{if $listing.inComparison.isTrue} checked="checked"{/if} />
				<span class="checked glyphicon glyphicon-bookmark" title="[[In Comparison:raw]]" data-toggle="tooltip" date-placement="top"></span>
				<span class="unchecked glyphicon glyphicon-bookmark" title="[[Compare Ad:raw]]" data-toggle="tooltip" date-placement="top"></span>
			</label>
		</li>
		<li>
			<a target="_blank" href="{page_path id='compared_listings'}" title="[[Compare Selected Listings:raw]]">
				<span class="glyphicon glyphicon-tasks"></span>
			</a>
		</li>
	</ul>
</div>
