<footer class="colorize-footer">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="about-us">
					<h2>[[About Us]]</h2>
					<img src="{url file='main^about_us.png'}">
					<p>
						[[You can change this text to any desired wording. In order to do so please go to the Admin Panel, then click "Site Layout" then "Page Templates" and then click on the "Edit" button across the "page_parts\footer_block.tpl" item in the table.]]
					</p>
					<span class="readMore"><a href="{page_path id='about'}">[[Read more]]</a></span>
				</div>
			</div>
			<div class="col-md-3">
				{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="publications" function="show_publications" passed_parameters_via_uri="" category_id="News" number_of_publications="2" publications_template="print_news_box_articles.tpl"}
			</div>
			<div class="col-md-3">
				{module name="recent_tweets" function="display_recent_tweets" count="3"}
			</div>
			<div class="col-md-3">
				{module name="poll" function="poll_form"}
			</div>
		</div>
		<div class="copyright-share">
			<div class="row">
				<div class="col-md-6 pull-right">
					{include file="miscellaneous^share_section.tpl"}
				</div>
				<div class="col-md-6 pull-left">
					<div class="copyright">
                        {IncludeCopyright}
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
