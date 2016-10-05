<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Radius Search Unit]]
    </label>
    <div class="col-sm-8">
        <select name="radius_search_unit" class="form-control">
            <option value="miles">[[Miles:raw]]</option>
            <option value="kilometers"{if $settings.radius_search_unit == 'kilometers'} selected{/if}>[[Kilometers:raw]]</option>
        </select>
    </div>
</div>
