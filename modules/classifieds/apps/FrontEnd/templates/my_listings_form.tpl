<form method="get" action="">
    <table class="form" cellspacing="4" cellpadding="2">
        <tr>
            <td>[[FormFieldCaptions!Listing ID]]</td>
            <td> {search property="id"}</td>
        </tr>
        <tr>
            <td>[[FormFieldCaptions!Category]]: </td>
            <td>{search property=$form_fields.category_sid.id template="category_tree_noredirect_no_digits.tpl"}</td>
        </tr>
        <tr>
            <td>[[FormFieldCaptions!Activation Date]]:</td>
            <td>{search property="activation_date"}</td>
        </tr>
        <tr>
            <td>[[FormFieldCaptions!Keywords]]:</td>
            <td>{search property="keywords"}</td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="action" value="search" />
                <input type="submit" value="[[Filter:raw]]" class="longButton" />
            </td>
        </tr>
    </table>
</form>
