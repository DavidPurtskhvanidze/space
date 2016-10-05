<div class="form-group">
    <label class="col-sm-3 control-label bolder">
      [[QR Code Configuration]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Error correction capacity]]
    </label>
    <div class="col-sm-8">
        <select name="qr_code_ecc" value="{$settings.qr_code_ecc}">
            <option value="L" {if $settings.qr_code_ecc == 'L'} selected{/if}>[[Level L - 7% of codewords can be restored]]</option>
            <option value="M" {if $settings.qr_code_ecc == 'M'} selected{/if}>[[Level M - 15% of codewords can be restored]]</option>
            <option value="Q" {if $settings.qr_code_ecc == 'Q'} selected{/if}>[[Level Q - 25% of codewords can be restored]]</option>
            <option value="H" {if $settings.qr_code_ecc == 'H'} selected{/if}>[[Level H - 30% of codewords can be restored]]</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Code Square Size (in pixels)]]
    </label>
    <div class="col-sm-8">
        <input type="text" size="5" name="qr_code_square_size" value="{$settings.qr_code_square_size}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Boundary size]]
    </label>
    <div class="col-sm-8">
        <input type="text" size="5" name="qr_code_boundary_size" value="{$settings.qr_code_boundary_size}">
        <div class="help-block">
            [[This parameter is measured in code squares. Please refer to the article in the User Manual -> Additional Features -> QR Code Generator to learn more about QR Code settings and how you can use them.]]
        </div>
    </div>
</div>
<div class="clearfix form-actions ClearBoth">
   <input type="submit" class="btn btn-default" value="[[Save:raw]]">
</div>
