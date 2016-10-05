<div class="searchForm searchFormManageComments">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
		  <li>[[Manage Comments]]</li>
    </ul>
	</div>
  <div class="page-content">
    <div class="page-header">
      <h1 class="lighter">[[Manage Comments]]</h1>
    </div>

    <div class="widget-box">
      <div class="widget-header header-color-dark">
        <h4 class="white" title="[[Click to hide the search form:raw]]">
					<a href="#" title="Collapse" data-action="collapse">
						<i class="icon-chevron-up"></i> [[Search Comments]]
					</a>
        </h4>
      </div>
      <div class="widget-body no-border">
        <div class="widget-main padding-14 no-padding-left no-padding-right">
          <form name="search_form" id="SearchCommentsForm" class="form form-horizontal">
            <input type="hidden" name="action" value="search">
              <div class="form-group">
                <label class="col-sm-2 control-label">[[Listing ID]]:</label>
                <div class="col-sm-8">{search property="listing_id"}</div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">[[Comment]]:</label>
                <div class="col-sm-8">{search property="comment"}</div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">[[Category]]:</label>
                <div class="col-sm-8">
                  <select name="category_sid[tree][]" class="form-control">
                    <option value="0">[[All Categories]]</option>
                  {include file="category_item_option.tpl" category=$categories level=0 selected=$current_category}
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">[[Username]]:</label>
                <div class="col-sm-8">{search property="username" template="string_with_autocomplete.tpl"}</div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">[[Status]]:</label>
                <div class="col-sm-8">{search property="published" template="list_for_published_status.tpl"}</div>
              </div>
              <div class="clearfix form-actions">
                <input type="submit" value="[[Find:raw]]" class="btn btn-default">
              </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.widget-box').filterState('manageComments');
	});
</script>
