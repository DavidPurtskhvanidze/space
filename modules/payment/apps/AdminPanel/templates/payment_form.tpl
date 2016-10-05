<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li>[[Payments]]</li>
  </ul>
</div>

<div class="page-content PaymentsBlock">
  <div class="page-header">
    <h1>[[Payments]]</h1>
  </div>

  <div class="row">
    <h4>[[Filter Payments By]]</h4>

    <form method="post" name="search_form">
      <input type="hidden" name="action" value="filter">
      <table class="table">
        <thead>
          <tr>
            <th>[[ID]]</th>
            <th>[[Period from]]</th>
            <th>[[to]]</th>
            <th>[[Username]]</th>
            <th>[[Payment State]]</th>
            <th>[[Payment Gateway]]</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="col-xs-1">{search property='sid'}</td>
            <td class="col-xs-2">{search property='creation_date' template='date.from.tpl'}</td>
            <td class="col-xs-2">{search property='creation_date' template='date.to.tpl'}</td>
            <td>{search property='username' template="string_with_autocomplete.tpl"}</td>
            <td>{search property='status'}</td>
            <td>{search property='payment_gateway'}</td>
            <td><input type="submit" value="[[Filter:raw]]" class="btn btn-default btn-sm"></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>

