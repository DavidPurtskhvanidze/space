<div class="searchForm">
    <div class="container">
        <div class="space-20"></div>
        <h1 class="page-title">[[Advanced Search]]</h1>
        {extension_point name='modules\main\apps\FrontEnd\IAdvancedSearchFormAdditionRenderer' categorySid = $category_sid}
        <div class="space-20"></div>
    </div>

    <div class="bg-grey">
        <div class="container">
            <div class="space-20"></div>
            <div class="space-20"></div>
            <div class="space-20"></div>
            <div class="row">
                <div class="col-sm-11">
                    <form class="form-horizontal advancedSearchForm" role="form" action="{page_path id='search_results'}">
                        <fieldset>
                            <input type="hidden" name="action" value="search"/>
                            <input type="hidden" name="category_sid[tree][]" value="{$category_sid}"/>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">[[Search]]</label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            {if $GLOBALS.settings.autocomplete_enable_in_keyword_search}
                                                {search property=$form_fields.keywords.id template="string_with_autocomplete.tpl" parameters=['element_id_prefix'=>'advancedSearch','preselection_fields'=>['category_sid']]}
                                            {else}
                                                {search property=$form_fields.keywords.id}
                                            {/if}
                                        </div>
                                        <div class="col-sm-6">
                                            {search property=$form_fields.category_sid.id template="category_tree.tpl"}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-5 col-sm-2 control-label">
                                    [[With pictures]]
                                </label>

                                <div class="col-xs-7 col-sm-10">
                                    {search property=$form_fields.pictures.id}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    [[Ad ID]]
                                </label>

                                <div class="col-sm-10">
                                    {search property=id}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    [[Posted within]]
                                </label>

                                <div class="col-sm-10">
                                    {search property=activation_date}
                                </div>
                            </div>

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
                            {foreach from=$form_fields item=form_field key=field_name}
                                {if !in_array($form_field.id, $fieldsToExclude) && $form_field.type != 'calendar'}
                                    <div class="form-group">
                                        {*{var_dump($form_field)}*}
                                        <label class="col-sm-2 control-label">
                                            [[FormFieldCaptions!{$form_field.caption}]]  <span class="fieldCaption {$form_field.type}"></span>
                                        </label>

                                        <div class="col-sm-10">
                                            {search property=$form_field.id}
                                        </div>
                                    </div>
                                {/if}
                            {/foreach}

                            {if isset($form_fields.Sold)}
                                <div class="form-group">
                                    <label class="col-xs-6 col-sm-2 control-label">
                                        [[FormFieldCaptions!Include sold items]]
                                    </label>

                                    <div class="col-xs-6 col-sm-10">
                                        {search property="Sold" template="include_sold.tpl"}
                                    </div>
                                </div>
                            {/if}
                        </fieldset>
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-orange h5">
                                    [[Search]]
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="space-20"></div>
        </div>
    </div>

</div>
