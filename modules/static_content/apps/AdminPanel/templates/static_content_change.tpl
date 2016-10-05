<div class="page-content">
    <div class="page-header">
        
    {display_error_messages}

    <form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
        {CSRF_token}
        <input type= "hidden" name= "action" value= "change">
        <input type="hidden" name="pageid" value={$pageid}>
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[ID]]
            </label>
            <div class="col-sm-8">
                <input type="text" name="new_pageid" value="{$pageid}" class="form-control">
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Static content name]]
            </label>
            <div class="col-sm-8">
                <input type="text" name="name" value="{$page.name}" class="form-control">
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Static content]]
            </label>
            <div class="col-sm-8">
                {WYSIWYGEditor name="content" width="100%" height="700" type="ckeditor"}{$page_content}{/WYSIWYGEditor}
            </div>
        </div> 
        <div class="clearfix form-actions">
            <input type="submit" value="[[Save:raw]]" class="btn btn-default">
        </div>
    </form>
    </div>
</div>
