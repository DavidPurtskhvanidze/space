<div class="fullWidthBlock footerBlock colorize-footer">
	<div class="fixedWidthBlock">
		<ul>
			<li class="footerCell footerCellAboutUs">
				<div>
					<h2>[[About Us]]</h2>
					<img src="{url file='main^about_us.png'}">
					[[You can change this text to any desired wording. In order to do so please go to the Admin Panel, then click "Site Layout" then "Page Templates" and then click on the "Edit" button across the "page_parts\footer_block.tpl" item in the table.]]
					<span class="readMore"><a href="{page_path id='about'}">[[Read more]]</a></span>
				</div>
			</li>
			<li class="footerCell footerCellPublications">
				<div>
				{module name="publications" function="show_publications" passed_parameters_via_uri="" category_id="News" number_of_publications="2" publications_template="print_news_box_articles.tpl"}
				</div>
			</li>
			<li class="footerCell footerCellRecentTweets">
				{module name="recent_tweets" function="display_recent_tweets" count="3"}
			</li>
			<li class="footerCell footerCellPoll">
				<div>
					{module name="poll" function="poll_form"}
				</div>
			</li>
		</ul>
		<div class="copyrightAndShareBoxWrapper">
			<div class="copyright">
                {IncludeCopyright}
			</div>
			{include file="miscellaneous^share_section.tpl"}
		</div>
	</div>
</div>
