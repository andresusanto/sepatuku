{extends file='includes/theme.tpl'}

{block name="body"}
<div id="common-page-header">
    <h1>{$article.title}</h1>
</div>

<div id="blog-article" class="common-content container">
    <div class="row sirclo-no-negative col-wrap">
        <div id="blog-articles-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar">
            <div class="sidebar-header">
                {sirclo_get_text text='recent_posts_title'}  
            </div>
            
            {if !empty($recent_posts)}
                {sirclo_render_blog_recent_posts recent_posts=$recent_posts}
            {/if}

            <div class="sidebar-header">
                {sirclo_get_text text='categories_title'}
            </div>

            {if !empty($categories)}
                <ul id="blog-categories">
                {foreach $categories as $category}
                    <li><a href="{$category.link}">{$category.name} ({$category.n_articles})</a></li>
                {/foreach}
                </ul>
            {/if}
            
            <div class="sidebar-header">
				{sirclo_get_text text='archives_title'}
            </div>

            {if !empty($archives)}
                {sirclo_render_blog_archives archives=$archives}
            {/if}
        </div>

        <div id="blog-articles-content" class="span-sirclo4-3 col">
            <div class="blog-articles-row">
                {if !empty($article.author_link)}
                    <p class="author">{sirclo_get_text text='misc_by'} <a href="{$article.author_link}">{$article.author}</a> {sirclo_get_text text='misc_on'} {$article.timestamp|date_format}</p>
                {else}
                    <p class="author">{sirclo_get_text text='misc_by'} {$article.author} {sirclo_get_text text='misc_on'} {$article.timestamp|date_format}</p>
                {/if}

                {if !empty($article.image)}
                    <p><img src="{sirclo_resource file=$article.image|sirclo_file_add_suffix:'_ori'}"></p>
                {/if}
                <p>{$article.snippet}</p>
				<!-- AddThis Button BEGIN -->
                <div class="addthis_toolbox addthis_default_style ">
                <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                <a class="addthis_button_tweet"></a>
                <a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal"></a>
                <a class="addthis_counter addthis_pill_style"></a>
                </div>
                <script type="text/javascript">var addthis_config = {ldelim}"data_track_addressbar":true{rdelim};</script>
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script>
                <!-- AddThis Button END -->
				</div>
			
        </div>
    </div>
    
</div>
{/block}