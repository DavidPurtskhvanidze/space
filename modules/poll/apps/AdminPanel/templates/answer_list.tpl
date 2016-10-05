<div class="page-content">
	<div class="page-header">
		<h4 class="headerBlue">[[Add a New Answer]]</h4>
	</div>


	<form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
        {CSRF_token}
		<input type="hidden" name="action" value="createanswer">
		<input type="hidden" name="question_id" value="{$question_id}">

		<div class="form-group">
			<label class="col-sm-3 control-label">
				[[Answer]]
			</label>

			<div class="col-sm-8">
				<input type="text" name="newanswer" value="" class="form-control">
			</div>
		</div>
		<div class="clearfix form-actions">
			<input type="submit" value="[[Add:raw]]" class="btn btn-default">
		</div>
	</form>

	{display_error_messages}

	<div class="row">
		<div class="col-xs-12">
			<table class="items sortable table table-striped table-hover">
				<thead>
				<tr class="head">
					<th colspan="3">[[Answers]]</th>
					<th colspan=2>[[Actions]]</th>
				</tr>
				{$colors=[]}
				{$rates=[]}
				{foreach from=$answers item=answer}
					{$colors[]=$answer.back_color}
					{$rates[]=$answer.ratio|number_format:2:".":""}
					<tr class="{cycle values="odd,even"}" data-item-sid="{$answer.id}">
						<td>[[{$answer.title|htmlspecialchars}]]</td>
						<td bgcolor="{$answer.back_color}"><span style="color:{$answer.text_color}">{$answer.ratio} %</span></td>
						<td>{$answer.counter} [[votes]]</td>
						<td><a onclick="return displayEditAnswerForm(this, {$question_id}, {$answer.id}, '{$answer.title|htmlentities|escape:"javascript"}', {$answer.counter})" class="itemControls edit"
						       href="?question_id={$question_id}&answer_id={$answer.id}" title="[[Edit:raw]]">[[Edit]]</a></td>
						<td><a class="itemControls delete" href="?action=deleteanswer&question_id={$question_id}&answer_id={$answer.id}" onclick="return confirm('[[Are you sure you want to delete this answer?:raw]]')" title="[[Delete:raw]]">[[Delete]]</a>
						</td>
					</tr>
				{/foreach}
				<tr>
					<th colspan="2">
						[[Total]]
					</th>
					<th colspan="3">
						{$total_answers}
					</th>
				</tr>
			</table>
		</div>
	</div>

	{if $total_answers}
		<img src="http://chart.apis.google.com/chart?chs=300x180&amp;cht=p3&amp;chco={$colors|join:"|"|replace:"#":""}&amp;chd=t:{$rates|join:","}&amp;chp=1&amp;chf=bg,s,00000000" alt=""/>
	{else}
		<p>[[No votes available at this time]]</p>
	{/if}

	<div class="editAnswerForm modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">[[Edit Answer]]</h4>
				</div>
				<div class="modal-body">

					<form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
                        {CSRF_token}
						<input type="hidden" name="action" value="changeanswer"/>
						<input type="hidden" name="answer_id" value=""/>
						<input type="hidden" name="question_id" value=""/>

						<div class="form-group">
							<label class="col-sm-4 control-label">[[Answer option]]</label>

							<div class="col-sm-8">
								<input type="text" name="title" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">[[Counter]]</label>

							<div class="col-sm-8">
								<input type="text" value="" name="counter">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-default">[[Save:raw]]</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
{require component="jquery" file="jquery.js"}
<script type="text/javascript">

	var $editAnswerForm = $(".editAnswerForm");

	$(function () {
		$editAnswerForm.modal({
			show: false
		});
	});

	function displayEditAnswerForm(link, questionId, answerId, title, counter) {
		$('form input[name=question_id]', $editAnswerForm).val(questionId);
		$('form input[name=answer_id]', $editAnswerForm).val(answerId);
		$('form input[name=title]', $editAnswerForm).val(title);
		$('form input[name=counter]', $editAnswerForm).val(counter);
		$editAnswerForm.modal('show');
		return false;
	}

</script>
