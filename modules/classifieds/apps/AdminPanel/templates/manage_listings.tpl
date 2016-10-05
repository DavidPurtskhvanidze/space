<div class="searchForm searchFormManageListings">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      <li>[[Manage Listings]]</li>
    </ul>
  </div>
  <div class="page-content">
    <div class="page-header">
	    <h1>[[Manage Listings]]</h1>
    </div>

    <div class="row">
      <div class="widget-box no-border">
        <div class="widget-header header-color-dark">
					<h4 class="white">
						<a href="#" data-action="collapse" title="collapse">
							<i class="icon-chevron-up"></i> [[Search Listings]]
						</a>
					</h4>
        </div>
        <div class="widget-body">
          <div class="widget-main padding-14 no-padding-left no-padding-right">
            <form class="form form-horizontal" name="search_form" id="SearchListingsForm" role="form">
              <input type="hidden" name="action" value="search">
              <div class="form-group">
                <label class="col-sm-3 control-label">[[Listing ID]]</label>
                <div class="col-sm-8">{search property="id"}</div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">[[Category]]:</label>
                <div class="col-xs-8">
                  <select name="category_sid[tree][]" class="form-control">
                    <option value="0">[[All Categories]]</option>
                    {include file="category_item_option.tpl" category=$categories level=0 selected=$current_category}
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">[[Listing Package]]:</label>
                <div class="col-sm-8">{search property="listing_package" template="list_for_packages.tpl"}</div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">[[Activation Date]]:</label>
                <div class="col-sm-8">{search property="activation_date"}</div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">[[Expiration Date]]:</label>
                <div class="col-sm-8"> {search property="expiration_date"}</div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">[[Username]]:</label>
                <div class="col-sm-8">
                  <div class="col-sm-6 no-padding">
                    {search property="username" template="string_with_autocomplete.tpl"}
                      {search property="user_sid" template="is_zero.tpl"} <span class="padding-10 middle">[[or]] [[search listings posted by administrator]]</span>
                  </div>
                </div>
              </div>
            {foreach $listingSearchExtraFields as $listingSearchExtraField}
              <div class="form-group">
                <label class="col-sm-3 control-label">[[{$listingSearchExtraField->getCaption()}]]:</label>
                <div class="col-sm-8">{search property=$listingSearchExtraField->getID()}</div>
              </div>
            {/foreach}
              <div class="form-group">
                <label class="col-sm-3 control-label">[[Moderation Status]]:</label>
                <div class="col-sm-8">{search property="moderation_status"}</div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">[[Active Status]]:</label>
                <div class="col-sm-8">{search property="active"  template="list_for_active_status.tpl"}</div>
              </div>
              <div class="clearfix form-actions">
                <input type="submit" value="[[Find:raw]]" class="btn btn-default">
              </div>
            </form>
            {include file="miscellaneous^toggle_search_form_js.tpl"}

            <script type="text/javascript">
              $(document).ready(function(){
                var searchAdminsListingsControll = $("input[type='checkbox'][name='user_sid[equal]']");
                var searchUsersListingsControll = $("input[name='username[like]']");

                searchUsersListingsControll.prop('disabled', searchAdminsListingsControll.prop('checked'));
                searchAdminsListingsControll.change(function(){
                  searchUsersListingsControll.prop('disabled', searchAdminsListingsControll.prop('checked'));
                });

								$('.widget-box').filterState('manageListings');
              });
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
