<select class="searchGeoDistance form-control" name="{$id}[geo][radius]" id="{$id}[geo][radius]" {$parameters}>
    <option value="any">[[Miscellaneous!Any Distance:raw]]</option>
    <option value="10" {if isset($value.geo.radius) && $value.geo.radius == 10}selected="selected"{/if}>[[Miscellaneous!Within:raw]] 10 [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]</option>
    <option value="20" {if isset($value.geo.radius) && $value.geo.radius == 20}selected="selected"{/if}>[[Miscellaneous!Within:raw]] 20 [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]</option>
    <option value="30" {if isset($value.geo.radius) && $value.geo.radius == 30}selected="selected"{/if}>[[Miscellaneous!Within:raw]] 30 [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]</option>
    <option value="40" {if isset($value.geo.radius) && $value.geo.radius == 40}selected="selected"{/if}>[[Miscellaneous!Within:raw]] 40 [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]</option>
    <option value="50" {if isset($value.geo.radius) && $value.geo.radius == 50}selected="selected"{/if}>[[Miscellaneous!Within:raw]] 50 [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]</option>
</select>
