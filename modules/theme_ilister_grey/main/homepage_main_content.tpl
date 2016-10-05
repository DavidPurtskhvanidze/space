{module name="main" function="display_template" template_file="google_adsense.tpl" do_not_modify_meta_data=true}
<div class="browsingTabs">
	<ul>
		<li><a href="#BrowseByCateroyTab"><h2>[[Browse by Category]]</h2></a></li>
		<li><span>//</span></li>
		<li><a href="#BrowseByStateTab"><h2>[[Browse by State]]</h2></a></li>
	</ul>
	<div id="BrowseByCateroyTab">
		{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="browse" category_id="root" fields="category_sid" browse_template="browse_by_categories_and_subcategories.tpl" number_of_cols="4" do_not_modify_meta_data=true}
	</div>
	<div id="BrowseByStateTab">
		{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="browse" category_id="root" fields="State" number_of_cols="6" browse_template="browse_by_state.tpl" do_not_modify_meta_data=true}
	</div>
</div>

{require component='jquery' file='jquery.js'}
<script type="text/javascript">
{literal}
$(document).ready(function(){
	function makeTabs(selector) {
		var tabContainers = $(selector + ' > div');
		tabContainers.hide().filter(':first').show();
		
		$(selector + ' > ul a').click(function () {
			tabContainers.hide();
			tabContainers.filter(this.hash).show();
			$(selector + ' > ul a').removeClass('selected');
			$(this).addClass('selected');
			return false;
		}).filter(':first').click();
	}
	makeTabs('.browsingTabs');
});
{/literal}
</script>
