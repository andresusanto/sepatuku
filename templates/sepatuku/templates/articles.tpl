{extends file='includes/theme.tpl'}

{block name="body"}

<div class="s-breadcrumb s-bottom-padding">
    <a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
    <span>&nbsp;/&nbsp;</span>
    <a href="{$links.blog}">{sirclo_get_text text='blog'}</a>
</div>

{if !empty($articles_title)}
<div id="common-page-header" class="col-md-12 s-content-title">
    <h1>{$articles_title}</h1>
    <hr/>
</div>
{else}
<div id="common-page-header" class="col-md-12 s-content-title">
    <h1>Articles</h1>
    <hr/>
</div>
{/if}

<div id="blog-articles" class="common-content container s-static-page">
    <div class="row sirclo-no-negative col-wrap">
        <div id="blog-articles-content" class="span-sirclo4-3 col col-md-8 s-content">
            {if !empty($articles)}
                {foreach $articles as $article}
                    {if $article@key > 0}
                        <!-- <hr/> -->
                    {/if}
                    {if !empty($article.image)}
                        <img src="{$article.image|sirclo_file_add_suffix:'_ori'}" class="s-fullwidth nomargin">
                    {/if}
                    <div class="row">
                        <div class="col-md-2">
                            <div class="s-blog-date s-top-margin">
                                21
                            </div>
                            <div class="s-blog-month">
                                JULI
                            </div>
                        </div>
                        <div class="blog-articles-row col-md-10">
                            <h3 class="no-bottom-margin">{$article.title}</h3>
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
                {/foreach}
            {/if}

            <hr/>

            {if !empty($articles_links)}
                <div id="blog-articles-paging">
                    <div class="prev-next text-left col-md-6">
                        {if !empty($articles_links.next)}
                            <a href="{$articles_links.next}" class="attention"><strong>{sirclo_get_text text='misc_newer_post'}</strong></a>
                        {/if}
                    </div>
                    <div class="prev-next text-right col-md-6">
                        {if !empty($articles_links.prev)}
                            <a href="{$articles_links.prev}" class="attention"><strong>{sirclo_get_text text='misc_older_post'}</strong></a>
                        {/if}
                    </div>
                </div>
            {/if}
        </div>
        <div id="blog-articles-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar col-md-4 s-static-sidebar s-top-margin">
            <!-- <div class="sidebar-header">
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
            </div> -->

            {if !empty($archives)}
                {sirclo_render_blog_archives archives=$archives}
            {/if}
        </div>
    </div>
    
</div>
{/block}