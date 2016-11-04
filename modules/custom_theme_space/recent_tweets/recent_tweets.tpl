    <div class="col-lg-9 col-sm-8">
        {display_error_messages}
        {if $responseError}
            <div class="error">
                {$responseError}
            </div>
        {/if}
    </div>
    {if !is_null($twitterTimeline)}
        {strip}
            {assign var=link_expr value='/<a(.*)href=\"(.*)\">(.*)<\/a>/uis'}
            {foreach from=$twitterTimeline.tweets item=tweet}
                <div class="col-lg-3 col-sm-4">
                    <div class="tweet-box">
                        <div class="tweet-box-text">{$tweet.text|regex_replace:$link_expr:''}</div>
                        <div class="tweet-box-date">
                            <a class="tweetPostedDate" href="http://twitter.com/{$twitterTimeline.user.screen_name}/statuses/{$tweet.id}" onclick="javascript:window.open(this.href, '_blank'); return false;" rel="nofollow">
                                {$tweet.posted_date}
                            </a>
                        </div>
                        <div class="row tweet-box-links">
                            <div class="col-xs-2 vcenter">
                                <span class="tweet-img"></span>
                            </div>
                            <div class="col-xs-10 vcenter">
                                {foreach $tweet.links as $link}
                                    {$link}
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}
        {/strip}

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
