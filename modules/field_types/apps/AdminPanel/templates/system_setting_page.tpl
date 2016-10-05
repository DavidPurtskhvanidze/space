{assign var="category" value='{"items":[{"id":"1","snippet":{"title":"Film & Animation"}},{"id":"2","snippet":{"title":"Autos & Vehicles"}},{"id":"10","snippet":{"title":"Music"}},{"id":"15","snippet":{"title":"Pets & Animals"}},{"id":"17","snippet":{"title":"Sports"}},{"id":"18","snippet":{"title":"Short Movies"}},{"id":"19","snippet":{"title":"Travel & Events"}},{"id":"20","snippet":{"title":"Gaming"}},{"id":"21","snippet":{"title":"Videoblogging"}},{"id":"22","snippet":{"title":"People & Blogs"}},{"id":"23","snippet":{"title":"Comedy"}},{"id":"24","snippet":{"title":"Entertainment"}},{"id":"25","snippet":{"title":"News & Politics"}},{"id":"26","snippet":{"title":"Howto & Style"}},{"id":"27","snippet":{"title":"Education"}},{"id":"28","snippet":{"title":"Science & Technology"}},{"id":"29","snippet":{"title":"Nonprofits & Activism"}},{"id":"30","snippet":{"title":"Movies"}},{"id":"31","snippet":{"title":"Anime/Animation"}},{"id":"32","snippet":{"title":"Action/Adventure"}},{"id":"33","snippet":{"title":"Classics"}},{"id":"34","snippet":{"title":"Comedy"}},{"id":"35","snippet":{"title":"Documentary"}},{"id":"36","snippet":{"title":"Drama"}},{"id":"37","snippet":{"title":"Family"}},{"id":"38","snippet":{"title":"Foreign"}},{"id":"39","snippet":{"title":"Horror"}},{"id":"40","snippet":{"title":"Sci-Fi/Fantasy"}},{"id":"41","snippet":{"title":"Thriller"}},{"id":"42","snippet":{"title":"Shorts"}},{"id":"43","snippet":{"title":"Shows"}},{"id":"44","snippet":{"title":"Trailers"}}]}'|json_decode:1}
{capture assign="opendIdAndOAuthSettingsLink"}#ThirdPartyAuthorization{/capture}
<div class="form-group">
    <label class="col-sm-3 control-label bolder">
      [[Upload Video to YouTube]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Upload Video Status {if $hasGoogleAccsessToken}enabled{else}disabled{/if}.]]
    </label>
    <div class="col-sm-8">
        <div class="form-control-static">
            <a href="{page_path module='field_types' function='google_auth'}">
                {if $hasGoogleAccsessToken}
                    [[Refresh Access Token]]
                {else}
                    [[Get Access Token]]
                {/if}
            </a>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Video Category]]
    </label>
    <div class="col-sm-8">
        <select name="youtube_category" class="form-control">
            {foreach $category.items as $item}
                <option value="{$item.id}"{if $settings.youtube_category == $item.id} selected{/if}>[[{$item.snippet.title}]]</option>
            {/foreach}
        </select>
    </div>
</div>

