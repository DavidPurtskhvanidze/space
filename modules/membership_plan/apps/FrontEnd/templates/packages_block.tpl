{foreach from=$packages item=package}
<hr />
<table>
    <tr>
        <td class="captionBold">[[Name]]</td>
        <td>{$package.name}</td>
    </tr>
    <tr>
        <td class="captionBold">[[Description]]</td>
        <td>{$package.description}</td>
    </tr>
    <tr>
        <td class="captionBold">[[Price]]</td>
        <td>{$package.price}</td>
    </tr>
    <tr>
        <td class="captionBold">[[Listing Lifetime]]</td>
        <td>{$package.listing_lifetime}</td>
    </tr>
</table>
{/foreach}
