<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li>[[Geographic Locations]]</li>
    </ul>
</div>
<div class="page-content">
    <div class="page-header">
        <h1>[[Geographic Locations]]</h1>
    </div>


    <div class="row">
        {display_error_messages}
        {display_success_messages}
        <div class="widget-box no-border">
            <div class="widget-header header-color-dark">
                <h4 class="white" title="[[Click to hide the search form:raw]]">
                    <a data-action="collapse" href="#">
                        <i class="icon-chevron-up"></i> [[Search Locations]]
                    </a>
                </h4>
            </div>
            <div class="widget-body">
                <div class="widget-main padding-6 no-padding-left no-padding-right">
                    <form name="search_form" id="SearchUsersForm" class="form form-horizontal">
                        <input type="hidden" name="action" value="search"/>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                [[Location ID]]
                            </label>

                            <div class="col-sm-6">
                                {search property="name" template="string.tpl"}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                [[Longitude]]
                            </label>

                            <div class="col-sm-6">
                                {search property="longitude" template="float_inline.tpl"}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">[[Latitude]]</label>

                            <div class="col-sm-6">
                                {search property="latitude" template="float_inline.tpl"}
                            </div>
                        </div>
                        <div class="clearfix form-actions">
                            <input type="submit" value="Search" class="btn btn-default">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <p><a class="btn btn-link"
              href="{page_path module='miscellaneous' function='geographic_data_actions'}">[[Add a New
                Location]]</a></p>
        {if $search.total_found > 0}
            <p>
                <a class="btn btn-link"
                   href="{page_path module='miscellaneous' function='geographic_data_actions'}?action=clear_data"
                   onclick="return confirm('[[Are you sure you want to delete all geographic locations?:raw]]')">[[Delete
                    all geographic locations]]</a>
            </p>
            <div class="col-xs-12 locationsBlock">
                <div class="table-responsive">
                    <div class="dataTables_wrapper" role="grid">
                        <div class="row">
                            <div class="col-sm-4">
                                {include file="miscellaneous^items_per_page_selector.tpl" search=$search}
                            </div>
                        </div>
                        <form method="post" id="manage_locations_form" name="itemSelectorForm">
                            {CSRF_token}
                            <table class="table table-striped table-hover dataTable">
                                <thead>
                                <tr role="row">
                                    <th>[[Location ID]]</th>
                                    <th>[[Longitude]]</th>
                                    <th>[[Latitude]]</th>
                                    <th>[[Actions]]</th>
                                </tr>
                                </thead>
                                <tbody>
                                {foreach from=$locations item=location}
                                    <tr>
                                        <td>{$location.name}</td>
                                        <td>{$location.longitude}</td>
                                        <td>{$location.latitude}</td>
                                        <td>
                                            <a class="btn btn-xs btn-info edit"
                                               href="{$GLOBALS.site_url}/geographic_data/edit_location/?sid={$location.sid}"
                                               title="[[Edit:raw]]">
                                                <i class="icon-edit"></i>
                                            </a>
                                            <a class="btn btn-xs btn-danger delete"
                                               href="{page_path module='miscellaneous' function='geographic_data_actions'}?action=delete&location_sid={$location.sid}"
                                               onclick="return confirm('[[Are you sure you want to delete these data?:raw]]')"
                                               title="[[Delete:raw]]">
                                                <i class="icon-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    {foreachelse}
                                    <tr>
                                        <td colspan="5">[[No geographic locations added]]</td>
                                    </tr>
                                {/foreach}
                                </tbody>
                            </table>
                        </form>
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                {include file="miscellaneous^page_selector.tpl" search=$search}
                            </div>
                        </div>
                    </div>
                    <!-- dataTables_wrapper -->
                </div>
            </div>
        {else}
            <p class="error">[[There are no locations available that match your search criteria. Please try to broaden
                your
                search criteria.]]</p>
        {/if}
    </div>

</div>
</div>
</div>
