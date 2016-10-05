<div class="my-listing-header">
	<div class="search-result-item-header">
	    <div class="row">
            <div class="col-xs-6">
                <ul class="list-inline left">
                    <li>
                       <div class="custom-form-control">
			                <input type="checkbox" id="listings{$listing.id}" name="listings[{$listing.id}]" value="1" class="item-selector" />
			                <label class="checkbox" for="listings{$listing.id}">&nbsp;</label>
			            </div>
                    </li>
                    <li>
                        <span class="fieldValue fieldValueListingRating">{include file="rating.tpl"}</span>
                    </li>
                </ul>
            </div>
	        <div class="col-xs-6 text-right">
	        	<div class="controls">
				    {include file=$listingControlsTemplate listingUrl=$listingUrl}
				</div>
	        </div>
	    </div>
	</div>
</div>
