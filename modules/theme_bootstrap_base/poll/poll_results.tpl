{title}[[Online Poll Results]]{/title}
<div class="pollResultsPage">
	<h1>[[Online Poll Results]]</h1>
	{foreach from=$questions item=question}
		<div>
			<h3>{$question.title}</h3>

			<div class="row">
				<div class="col-md-6">
					<table class="table">
						{$colors=[]}
						{$rates=[]}
						{foreach from=$question.answers item=answer}
							{$colors[]=$answer.back_color}
							{$rates[]=$answer.rate|number_format:2:".":""}
							<tr>
								<td>{$answer.title|htmlspecialchars}</td>
								<td class="text-right">
									<span class="label" style="color:{$answer.text_color}; background:{$answer.back_color};">{$answer.rate}%</span>
								</td>
								<td class="text-right">{$answer.counter} [[vote(s)]]</td>
							</tr>
						{/foreach}
						<tr>
							<th colspan="2">[[Total]]</th>
							<th class="text-right">{$question.total_votes} [[vote(s)]]</th>
						</tr>
					</table>
				</div>
				<div class="col-md-6">
					{if $question.total_votes}
						<img src="http://chart.apis.google.com/chart?chs=300x180&amp;cht=p3&amp;chco={$colors|join:"|"|replace:"#":""}&amp;chd=t:{$rates|join:","}&amp;chp=1&amp;chf=bg,s,00000000" alt=""/>
					{else}
						<p>[[No votes available at this time]]</p>
					{/if}
				</div>
			</div>
		</div>
	{/foreach}
</div>
