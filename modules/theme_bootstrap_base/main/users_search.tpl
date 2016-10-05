{extends 'default.tpl'}

{block name="mainContent"}
	<div class="container">
		<div class="row">
			<div class="col-sm-9">
				{$MAIN_CONTENT}
			</div>
			<div class="col-sm-3">
				{module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings_vertical.tpl" number_of_rows="4" number_of_cols="1" do_not_modify_meta_data=true}
			</div>
		</div>
	</div>
{/block}
