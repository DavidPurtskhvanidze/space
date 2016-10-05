{assign var="fieldsToExclude"
value=[
    "keywords",
    "pictures",
    "ZipCode",
    "activation_date",
    "Title",
    "Description",
    "Location",
    "Video",
    "sid",
    "id",
    "feature_featured",
    "feature_slideshow",
    "feature_youtube",
    "feature_highlighted",
    "feature_sponsored",
    "feature_youtube_video_id",
    "category_sid",
    "type",
    "moderation_status",
    "package",
    "user",
    "username",
    "active",
    "views",
    "category",
    "Sold"
]}


<section class="advanced-search-block">
    <h1 class="title">
        [[Advanced Search]]
    </h1>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="searchForm">
                {extension_point name='modules\main\apps\FrontEnd\IAdvancedSearchFormAdditionRenderer' categorySid = $category_sid}
                <form role="form" action="{page_path id='search_results'}">
                    <fieldset>
                        <input type="hidden" name="action" value="search"/>
                        <input type="hidden" name="category_sid[tree][]" value="{$category_sid}"/>

                        <div class="form-group">
                            <label class="control-label">[[Keywords]]</label>
                            {if $GLOBALS.settings.autocomplete_enable_in_keyword_search}
                                {search property=$form_fields.keywords.id template="string_with_autocomplete.tpl" placeholder="Keywords" parameters=['element_id_prefix'=>'advancedSearch','preselection_fields'=>['category_sid']]}
                            {else}
                                {search property=$form_fields.keywords.id placeholder="Keywords"}
                            {/if}
                        </div>

                        <div class="form-group">
                            <label class="control-label">[[Category]]</label>
                            {search property=$form_fields.category_sid.id template="category_tree.tpl" placeholder="Category"}
                        </div>

                        <div class="form-group">
                            <label class="control-label">[[Ad ID]]</label>
                            {search property=id placeholder="Ad ID"}
                        </div>

                        <div class="form-group">
                            {capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}
                            {i18n->getDateFormat assign="date_format"}
                            <label class="control-label">[[Posted within]] / [[date format: '{$date_format}', for example: '{$date_format_example}']]</label>
                            {search property=activation_date}
                        </div>

                        {foreach from=$form_fields item=form_field key=field_name}
                            {if !in_array($form_field.id, $fieldsToExclude) && $form_field.type != 'calendar'}
                                <div class="form-group">
                                    <label class="control-label">
                                        [[FormFieldCaptions!{$form_field.caption}]] <span class="fieldCaption {$form_field.type}"></span>
                                    </label>
                                    {search property=$form_field.id placeholder="{$form_field.caption}"}
                                </div>
                            {/if}
                        {/foreach}

                        {if isset($form_fields.Sold)}
                            <div class="form-group">
                                <label class="control-label">[[FormFieldCaptions!Include sold items]]</label>
                                {search property="Sold" template="include_sold.tpl" placeholder="Include sold items"}
                            </div>
                        {/if}

                        <div class="form-group">
                            <label class="control-label">[[With pictures only]]</label>
                            {search property=$form_fields.pictures.id}
                        </div>

                    </fieldset>
                    <div class="form-group text-center">
                        <button type="submit" class="default-button wb">
                            [[Find]]
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<script>
    $(function() {
        $('input[type="checkbox"]').bootstrapToggle({
            on: 'On',
            off: 'Off',
            size: 'small'
        });
    })
</script>
