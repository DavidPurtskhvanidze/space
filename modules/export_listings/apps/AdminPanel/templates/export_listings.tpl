<div class="exportListings">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      <li>[[Export Listings]]</li>
    </ul>
  </div>

  <div class="page-content">
    <div class="page-header">
      <h1>[[Export Listings]]</h1>
    </div>

    <div class="alert alert-warning">
      [[You can export some or all listings in an Excel file format (csv export is not supported). The resulting archive will contain all listings and all files associated with them.]]
    </div>

    <div class="row">
      {display_error_messages}

      <form class="form form-horizontal" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="export">
        <h4 class="headerBlue">[[Export Filter]]</h4>
        <div class="form-group">
          <label class="col-sm-3 control-label">[[Listing ID]]</label>
          <div class="col-sm-8"> {search property="id"}</div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">[[Category]]</label>
          <div class="col-sm-8">{search property="category_sid" template="category_tree.tpl"}</div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">[[Activation Date]]</label>
          <div class="col-sm-8">{search property="activation_date"}</div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">[[Expiration Date]]</label>
          <div class="col-sm-8">{search property="expiration_date"}</div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">[[Username]]</label>
          <div class="col-sm-8">{search property="username"}</div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">[[Featured Ad]]</label>
          <div class="col-sm-8">{search property="feature_featured"}</div>
        </div>

        <h4 class="headerBlue">[[Listing Properties To Export]]</h4>
          <div class="row">
            <div class="control-group">
              {foreach from=$properties item=property name=properties}
                <div class="col-xs-6 col-sm-3 col-md-3">
                  <div class="checkbox">
                    <label>
                      <input class="ace" type="checkbox" name="export_properties[]" value="{$property.id}" />
                      <span class="lbl"> [[FormFieldCaptions!{$property.caption}]]</span>
                    </label>
                  </div>
                </div>
              {/foreach}
            </div>

            <div class="col-sm-12">
              <div class="space-8"></div>
              <a href="#" onClick="check_all();return false;">[[Select all]]</a> | <a href="#" onClick="uncheck_all();return false;">[[Deselect all]]</a>
            </div>
          </div>


        <div class="clearfix form-actions">
          <input type="submit" value="[[Export:raw]]" class="btn btn-default">
        </div>
      </form>

      <script type="text/javascript">
      {literal}
        function check_all()
        {
          $('input[name^=export_properties]').prop("checked", true);
          return false;
        }
        function uncheck_all() {
          $('input[name^=export_properties]').prop("checked", false);
          return false;
        }
      {/literal}
      </script>
    </div>
  </div>
</div>
