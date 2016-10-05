<form action="" method="post" id="SaveSearchForm">
    {CSRF_token}
    <div class="saveSearchForm">
        <input type="hidden" name="action" value="save" />
        <input type="hidden" name="searchId" value="{$searchId}" />
        <p>[[Search Name]]: <input type="text" name="search_name" class="form-control"> <input type="submit" value="[[Save:raw]]" class="button" /></p>
    </div>
</form>
