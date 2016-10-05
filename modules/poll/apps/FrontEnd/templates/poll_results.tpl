{title}[[Online Poll Results]]{/title}
<div class="pollResultsPage">
	<h1>[[Online Poll Results]]</h1>
	{foreach from=$questions item=question}
		<div class="poll">
			<h3>{$question.title}</h3>
			<div class="pollDescription">
				{$colors=[]}
				{$rates=[]}
				{foreach from=$question.answers item=answer}
					{$colors[]=$answer.back_color}
					{$rates[]=$answer.rate|number_format:2:".":""}
					<div class="answerInfo">
						<span class="answerTitle">{$answer.title|htmlspecialchars}</span>
						<span class="answerRate" style="color:{$answer.text_color}; background:{$answer.back_color};">{$answer.rate} %</span>
						<span class="answerVotes">{$answer.counter} [[vote(s)]]</span>
					</div>
				{/foreach}
				<span class="answerTotalVotes">[[Total votes]]: {$question.total_votes}</span>
			</div>
			<div class="pollDiagram">
				{if $question.total_votes}
					<img src="http://chart.apis.google.com/chart?chs=300x180&amp;cht=p3&amp;chco={$colors|join:"|"|replace:"#":""}&amp;chd=t:{$rates|join:","}&amp;chp=1&amp;chf=bg,s,00000000" alt="" />
				{else}
					<p style="padding-left: 20px">[[No votes available at this time]]</p>
				{/if}
			</div>
		</div>
    {/foreach}
</div>
