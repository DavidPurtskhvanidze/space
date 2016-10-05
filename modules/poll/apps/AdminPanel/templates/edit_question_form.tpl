<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li><a href="{$GLOBALS.site_url}{$GLOBALS.current_page_uri}">[[Polls]]</a>
	&gt; [[Edit Question]]</li>
    </ul>
</div>
<div class="page-content">
    <div class="page-header">
        <h1 class="lighter">[[Edit Question]]</h1>
    </div>
        <div class="form pollQuestion">
            <form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
                {CSRF_token}
                <input type="hidden" name="question_id" value={$question.id}>
                <input type="hidden" name="action" value="changequestion">
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                      [[Question]]
                    </label>
                    <div class="col-sm-8">
                        <textarea name="title" class="form-control">{$question.title}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                      [[Active]]
                    </label>
                    <div class="col-sm-8">
                        <div class="checkbox">
                            <input type="hidden" name="activity" value="0">
                            <label>
                                <input class="ace ace-switch ace-switch-6" type="checkbox" name="activity" value="1" {if $question.activity}checked{/if}>
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                      [[Display Results to Visitors]]
                    </label>
                    <div class="col-sm-8">
                        <div class="checkbox">
                            <input type="hidden" name="display" value="0">
                            <label>
                                <input class="ace ace-switch ace-switch-6" type="checkbox" name="display" value="1" {if $question.display}checked{/if}>
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <input type="submit" value="[[Save:raw]]" class="btn btn-default">
                </div>
            </form>
        </div>
</div>
