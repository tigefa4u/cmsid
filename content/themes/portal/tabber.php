<div class="tabber">
	<ul id="tabs" class="tabs">
		<li><a href="#popular-posts" rel="popular-posts" class="selected">Popular</a></li>
		<li><a href="#recent-comments" rel="recent-comments">Comments</a></li>
		<li><a href="#monthly-archives" rel="monthly-archives">Archives</a></li>
		<li><a href="#tag-cloud" rel="tag-cloud">Tags</a></li>
	</ul>
<div class="clear"></div>
	<ul id="popular-posts" class="tabcontent">
        <?php tp_popular_posts(); ?>
	</ul>
	<ul id="recent-comments" class="tabcontent">
        <?php tp_recent_comments(); ?>
	</ul>
	<ul id="monthly-archives" class="tabcontent">
		<?php get_archives('type=monthly&limit=12'); ?>
	</ul>
	<ul id="tag-cloud" class="tabcontent">
		<?php tag_cloud('smallest=8&largest=22'); ?>
	</ul>
<script type="text/javascript">
	var tabs=new ddtabcontent("tabs")
	tabs.setpersist(false)
	tabs.setselectedClassTarget("link")
	tabs.init()
	</script>
</div> <!--end: tabber-->
<div class="clear"></div>