<form method="post" action="{page_path module='poll' function='vote_for_answer'}">
	<div class="pollBlock">
		<input type="hidden" name="votequestion" value="{$question.id}" />
        {CSRF_token}

		<h2>{$question.title|escape}</h2>
		<ul>
			{foreach from=$answers item="answer"}
			<li>
				<label>
					<span class="pollControl"><input type="radio" name="voteanswer" value="{$answer.id}" /></span>
					<span class="pollAnswer">{$answer.title|escape}</span>
				</label>
			</li>
			{/foreach}
		</ul>
		<div>
			<input type="submit" class="longButton btn btn-default" value="[[Vote:raw]]" />
			<a class="viewResults" href='{page_path module='poll' function='vote_results'}'>[[View poll results]]</a>
		</div>
	</div>
</form>
