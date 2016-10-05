<div class="searchForm editListValues">
    <div class="breadcrumbs">
        <ul class="breadcrumb">
            <li>[[Manage Forms]]</a></li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>[[Edit Form {$form_info.id}]]</h1>
        </div>
        {display_error_messages}
        {display_success_messages}
        <h4>[[Manage Forms]]</h4>

        <div class="modulesTabs">
            <a href="{page_path module='form_manager' function='add_form'}?application_id={$application_id}">[[Add
                Form]]</a>

            <div id="Available">
                <form method="post" name="itemSelectorForm">
                    <table class="items sortable table table-striped table-hover">
                        {CSRF_token}
                        <tr class="head">
                            <th>[[ID]]</th>
                            <th>[[Title]]</th>
                            <th>[[Category]]</th>
                            <th>[[Actions]]</th>
                        </tr>
                        {foreach from=$forms item=value}
                            <tr class="{cycle values='odd,even'}">
                                <td>{$value.id}</td>
                                <td>{$value.title}</td>
                                <td>{$value.category_sid}</td>
                                <td>
                                    <a class="itemControls edit btn btn-xs btn-info"
                                       href="{page_path module='form_manager' function='edit_form'}?sid={$value.sid}&appication_id={$application_id}"
                                       title="[[Edit]]" data-rel="tooltip" data-original-title="[[Edit]]"><i
                                                class="icon-edit bigger-120"></i></a>
                                    <a class="itemControls delete btn btn-xs btn-danger"
                                       href="?action=delete&sid={$value.sid}&appication_id={$application_id}"
                                       onclick='return confirm("[[Are you sure that you want to delete this form?:raw]]")'
                                       title="[[Delete]]" data-rel="tooltip" data-original-title="[[Delete]]"><i
                                                class="icon-trash bigger-120"></i></a>
                                </td>
                            </tr>
                            {foreachelse}
                            <tr>
                                <td colspan="5">[[No forms found]]</td>
                            </tr>
                        {/foreach}
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
