{extends file='includes/theme.tpl'}

{block name="body"}

{if !empty($articles_title)}
<div id="common-page-header">
    <h1>{$articles_title}</h1>
</div>
{/if}

<div id="blog-articles" class="common-content container">
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
            {if !empty($articles)}
                {foreach $articles as $article}
                    {if $article@key > 0}
                        <hr/>
                    {/if}

                    <div class="blog-articles-row">
                        <h2><a href="{$article.link}">{$article.title}</a></h2>
                        {if !empty($article.author_link)}
                            <p class="author">{sirclo_get_text text='misc_by'} <a href="{$article.author_link}">{$article.author}</a> {sirclo_get_text text='misc_on'} {$article.timestamp|date_format}</p>
                        {else}
                            <p class="author">{sirclo_get_text text='misc_by'} {$article.author} {sirclo_get_text text='misc_on'} {$article.timestamp|date_format}</p>
                        {/if}

                        {if !empty($article.image)}
                            <p><img src="{$article.image|sirclo_file_add_suffix:'_ori'}"></p>
                        {/if}
                        <p>{$article.snippet}</p>
                        {if isset($article.is_complete) and !$article.is_complete}
                            <a class="read-more" href="{$article.link}">{sirclo_get_text text='read_more_link'}..</a>
                        {/if}
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
    </div>
    
</div>
{/block}