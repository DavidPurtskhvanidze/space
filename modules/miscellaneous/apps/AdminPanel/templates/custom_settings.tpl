<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li>[[Custom Settings]]</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Custom Settings]]</h1>
  </div>

  <div class="row">
    <a class="btn btn-link" href="{page_path module='miscellaneous' function='add_custom_setting'}">[[Add a New Custom Setting]]</a>

    {display_error_messages}

      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>[[ID]]</th>
            <th>[[Value]]</th>
            <th>[[Actions]]</th>
          </tr>
        </thead>
        <tbody>
          {foreach from=$custom_settings item=setting_info}
            <tr>
              <td>{$setting_info.id}</td>
              <td>{$setting_info.value}</td>
              <td>
                <a class="btn btn-xs btn-info edit" href="{page_path module='miscellaneous' function='edit_custom_setting'}?sid={$setting_info.sid}" title="[[Edit:raw]]">
                  <i class="icon-edit"></i>
                </a>
                <a class="btn btn-xs btn-danger delete" href="?action=delete&sid={$setting_info.sid}" onclick="return confirm('[[Are you sure want to delete this setting?:raw]]')" title="[[Delete:raw]]">
                  <i class="icon-trash"></i>
                </a>
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>

    {capture assign="mobileAddonUrl"}http://www.worksforweb.com/classifieds-software/addons/mobile-addon/{/capture}

    <div class="alert alert-info">
      uri_of_listing_details_page_on_mobile_frontend - [[This setting is used in case your website has the <a href="$mobileAddonUrl">Mobile Frontend Addon</a>.]]
    </div>
  </div>
</div>
