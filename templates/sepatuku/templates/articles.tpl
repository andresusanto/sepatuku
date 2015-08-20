<!-- 
SEPATUKU SIRCLO THEME 
 (c) 2015 by AMBISNIS.COM
 Enquiry: sales@ambisnis.com
-->

{extends file='includes/theme.tpl'}

{block name="body"}
<div class="row s-main-content s-static-page">
	<div class="col-md-12 s-breadcrumb s-bottom-padding">
		<a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
		<span>&nbsp;/&nbsp;</span>
		<a href="{$links.blog}">{sirclo_get_text text='blog'}</a>
	</div>
	<div class="col-md-12 s-content-title">
		<h1>{sirclo_get_text text='blog'}</h1>
		<hr/>
	</div>
	
	
	<div id="blog-articles-content" class="span-sirclo4-3 col col-md-8 s-content">
		{if !empty($articles)}
			{foreach $articles as $article}
				{if $article@key > 0}
					<hr/>
				{/if}

				<div class="blog-articles-row">
					{if !empty($article.image)}
						<p><img class="s-fullwidth nomargin" src="{$article.image}"></p>
					{/if}
					
					<div class="row">
						<div class="col-md-2">
							<div class="s-blog-date s-top-margin">
								{date('d', strtotime($article.timestamp|date_format))}
							</div>
							<div class="s-blog-month">
								{date('m', strtotime($article.timestamp|date_format))}
							</div>
						</div>
						
						<div class="col-md-10">
							<h2><a href="{$article.link}">{$article.title}</a></h2>
							{if !empty($article.author_link)}
								<p class="author">{sirclo_get_text text='misc_by'} <a href="{$article.author_link}">{$article.author}</a> {sirclo_get_text text='misc_on'} {$article.timestamp|date_format}</p>
							{else}
								<p class="author">{sirclo_get_text text='misc_by'} {$article.author} {sirclo_get_text text='misc_on'} {$article.timestamp|date_format}</p>
							{/if}

							<p>{$article.snippet}</p>	
							{if isset($article.is_complete) and !$article.is_complete}
								<a class="read-more" href="{$article.link}">{sirclo_get_text text='read_more_link'}..</a>
							{/if}
						</div>
					</div>
				</div>
			{/foreach}
		{/if}

		<hr/>

		{if !empty($articles_links)}
			<div id="blog-articles-paging">
				<div class="prev-next text-left">
					{if !empty($articles_links.next)}
						<a href="{$articles_links.next}">{sirclo_get_text text='misc_newer_post'}</a>
					{/if}
				</div>
				<div class="prev-next text-right">
					{if !empty($articles_links.prev)}
						<a href="{$articles_links.prev}">{sirclo_get_text text='misc_older_post'}</a>
					{/if}
				</div>
			</div>
		{/if}
	</div>
	
	
	<div class="col-md-4 s-static-sidebar s-top-margin">
		{if !empty($archives)}
			{sirclo_render_blog_archives archives=$archives}
		{/if}
	</div>
</div>
{/block}

{block name="footer"}
    <script type="text/javascript">
        $('.sirclo-form').validate();
    </script>
    {if !empty($configs.theme_facebook_appid)}
    <script type="text/javascript">
        $(document).ready(function() {
          $.getScript('//connect.facebook.com/en_UK/all.js', function(){
            FB.init({
              appId  : '{$configs.theme_facebook_appid}',
              cookie : true,  // enable cookies to allow the server to access
                              // the session
              xfbml  : true,  // parse social plugins on this page
              version: 'v2.0' // use version 2.0
              });
            });
          if (location.search) {
              $("#fbloginlink").facebooklogin("{$links.cart_place_order}");
          } else {
              $("#fbloginlink").facebooklogin("{$links.account_login}");                       
          }
        });
    </script>
    {/if}
{/block}
