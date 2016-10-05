<select class="searchGeoDistance" name="{$id}[geo][radius]" id="{$id}" {$parameters} >
	<option value="any">[[Miscellaneous!Any Distance:raw]]</option>
	<option value="10" {if $value.geo.radius == 10}selected{/if}>[[Miscellaneous!Within:raw]] 10 [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]</option>
	<option value="20" {if $value.geo.radius == 20}selected{/if}>[[Miscellaneous!Within:raw]] 20 [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]</option>
	<option value="30" {if $value.geo.radius == 30}selected{/if}>[[Miscellaneous!Within:raw]] 30 [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]</option>
	<option value="40" {if $value.geo.radius == 40}selected{/if}>[[Miscellaneous!Within:raw]] 40 [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]</option>
	<option value="50" {if $value.geo.radius == 50}selected{/if}>[[Miscellaneous!Within:raw]] 50 [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]</option>
</select>
