<form action="" method="post" id="SaveSearchForm">
    {CSRF_token}
    <div class="saveSearchForm row">
        <input type="hidden" name="action" value="save" />
        <input type="hidden" name="searchId" value="{$searchId}" />
        <div class="col-sm-9">
            <input type="text" name="search_name" class="form-control" placeholder="[[Search Name]]" />
        </div>
        <div class="col-sm-3">
            <input type="submit" value="[[Save:raw]]" class="button btn btn-default"/>
        </div>
    </div>
</form>
