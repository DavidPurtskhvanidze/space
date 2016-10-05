<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li>[[Add Listing]]</li>
	</ul>
</div>
<div class="page-content">
	<div class="page-header">
		<h1>[[Add Listing]]</h1>
	</div>
	<div class="row">
		<div class="alert alert-info">[[You can add new listings via the admin panel. Such listings will belong to the Administrator. When you created a listing you need to activate it. Please select the appropriate category to post a listing.]]</div>
		{assign var="level" value=0}
		{include file="add_listing_choose_category_node.tpl" category=$category level=$level package=$listing_package_id}
	</div>
</div>
