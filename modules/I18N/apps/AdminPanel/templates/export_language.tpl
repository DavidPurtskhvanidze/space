<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li>[[Export Language]]</li>
    </ul>
</div>
<div class="page-content">
    <div class="page-header">
        <h1 class="lighter">[[Export Language]]</h1>
    </div>
    <div class="row">
        {display_error_messages}

        <form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
            {CSRF_token}
            <input type="hidden" name="action" value="export_language">
            <div class="form-group">
                <label class="col-sm-3 control-label">
                  [[Select language to export]]
                </label>
                <div class="col-sm-5">
                    <select name="languageId" class="form-control">
                        {foreach from=$languages item=lang}
                            <option value="{$lang.id}">{$lang.caption}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="clearfix form-actions">
                <input type="submit" value="[[Export:raw]]" class="btn btn-default">
             </div>
        </form>
    </div>
</div>
