<div class="recentTweets">
	<h2>[[Recent Tweets]]</h2>

	{display_error_messages}
	{if $responseError}
		<div class="error">
			{$responseError}
		</div>
	{/if}

	{if !is_null($twitterTimeline)}
		<ul class="twitterList">
			{foreach from=$twitterTimeline.tweets item=tweet}
				<li>
					<div class="tweetText">{$tweet.text}</div>
					<div class="tweetPostedDateWrapper">
						<a class="tweetImage" href="http://twitter.com/{$twitterTimeline.user.screen_name}/statuses/{$tweet.id}" onclick="javascript:window.open(this.href, '_blank'); return false;" rel="nofollow">&nbsp;</a>
						<a class="tweetPostedDate" href="http://twitter.com/{$twitterTimeline.user.screen_name}/statuses/{$tweet.id}" onclick="javascript:window.open(this.href, '_blank'); return false;" rel="nofollow">{$tweet.posted_date}</a>
					</div>
				</li>
			{/foreach}
		</ul>
		<span class="readMore"><a href="https://twitter.com/#!/{$twitterTimeline.user.screen_name}" onclick="javascript:window.open(this.href, '_blank'); return false;" rel="nofollow">[[View all tweets]]</a></span>

		{require component='jquery' file='jquery.js'}
		<script type="text/javascript">
			function relative_time(time_value) {
				var values = time_value.split(" ");
				time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
				var parsed_date = Date.parse(time_value);
				var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
				var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
				delta = delta + (relative_to.getTimezoneOffset() * 60);

				if (delta < 60) {
					return 'less than a minute ago';
				} else if(delta < 120) {
					return 'about a minute ago';
				} else if(delta < (60*60)) {
					return (parseInt(delta / 60)).toString() + ' minutes ago';
				} else if(delta < (120*60)) {
					return 'about an hour ago';
				} else if(delta < (24*60*60)) {
					return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
				} else if(delta < (48*60*60)) {
					return '1 day ago';
				} else {
					return (parseInt(delta / 86400)).toString() + ' days ago';
				}
			}

			$(document).ready(function() {
				$('.tweetPostedDate').each(function() {
					this.innerHTML = relative_time(this.innerHTML);
				});
			});
		</script>
	{/if}
</div>
