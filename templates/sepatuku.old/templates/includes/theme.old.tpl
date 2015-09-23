{if empty($smarty.get.viewmode)}

{capture}
    {include file='includes/functions.tpl'}
{/capture}
<!DOCTYPE html>
<html lang="en">
    <head>
        {if !empty($title)}
            <title>{$title}</title>
        {else}
            <title>Page Not Found</title>
        {/if}
        
        <meta charset="utf-8">
        {if !empty($meta)}
            {foreach $meta as $meta_content}
                <meta name="{$meta_content@key}" content="{$meta_content}">
            {/foreach}
        {/if}

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:title" content="{$title}" />
        <meta property="og:url" content="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" />
        {if !empty($product.images)}<meta property="og:image" content="{$product.images['0']}" />{/if}
        {if !empty($configs.theme_favicon_url)}
            <link rel="icon" type="image/png" href="{$configs.theme_favicon_url}"/>
        {/if}

        <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>

        {sirclo_render_css}
        {$_body_css = 'rel="stylesheet" type="text/css"'}
         <link {$_body_css} href="{sirclo_resource file='css/nivoslider/light.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/nivoslider/nivo-slider.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/bootstrap.min.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/custom.css'}">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/flick/jquery-ui.min.css">
         <link {$_body_css} href="{sirclo_resource file='css/ccdialog.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/widget.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/quickview.css'}">
        {if !empty($configs.theme_background_image)}{literal}<style>body{background: url('{/literal}{$configs.theme_background_image}{literal}') repeat;} </style>{/literal}{/if}
        {block name=header}{/block}
    
        {if !empty($static_contents['Scripts'])}
        {$static_contents['Scripts']|regex_replace:"/(<p>|<p [^>]*>|<\\/p>)/":""}
    {/if}
    </head>
    <body>  
        <div id="header-top" class="box-shadow">
            <div class="container">
                <div class="row">
                    <div id="header-site-logo" class="span6">
                        {if !empty($logo_url)}
                            <a href="/"><img src="{$logo_url}"></a>
                        {/if}
                    </div>

                    <div id="header-shopping-cart" class="span6">
                        {if !empty($links)}
                            <div class="member-links">
                                <a href="{$links.payment_notif}">{sirclo_get_text text='confirm_payment_link'}</a> | 
                                {if !empty($member)}
                                    <a href="{$links.account}">{sirclo_get_text text='my_account_link'}</a> | 
                                    <a style="padding-right:0px;" href="{$links.account_logout}">{sirclo_get_text text='logout_link'}</a>
                                {else}
                                    <a href="{$links.account_register}">{sirclo_get_text text='register_link'}</a> | 
                                    <a class="last" href="{$links.account_login}">{sirclo_get_text text='login_link'}</a> <span class="visible-phone">|</span>
                                {/if}

                                <a href="{$links.cart}" class="visible-phone">
                                    Cart
                                    {if !empty($cart) and !empty($cart.grand_total)}
                                        ({$cart.total_items})
                                    {else}
                                        (0)
                                    {/if}    
                                </a>
                            </div>

                            <img src="/images/icon-shopping-cart.png" class="hidden-phone">

                            <a href="{$links.cart}" class="hidden-phone">
                                {if !empty($cart) and !empty($cart.grand_total)}
                                    {call skeleton_pluralize singular="item" plural="items" count=$cart.total_items} / {$active_currency} {$cart.grand_total|number_format:2}
                                {else}
                                    {call skeleton_pluralize singular="item" plural="items" count=0} / {$active_currency} {0|number_format:2}
                                {/if}
                            </a>
                        {/if}
                    </div>
                </div>

                <div class="navbar">
                    <div class="navbar-inner">
                        <button type="button" class="btn hidden-desktop" data-toggle="collapse" data-target=".nav-collapse">
                            &#9776;
                            NAVIGATION
                        </button>

                        <div class="nav-collapse">
                            <ul class="nav">
                                {if !empty($main_nav)}
                                    {call skeleton_render_main_navbar nav_links=$main_nav is_submenu=false}
                                {/if}
                            </ul>    
                        </div>

                        {if !empty($links)}
                            <form id="search-form" class="navbar-form pull-right" action="{$links.products_search}">
                                <input name="query" type="text" class="span2" value="">
                                <button type="submit" class="btn-flat">{sirclo_get_text text='misc_search'}</button>
                            </form>
                        {/if}
                    </div>
                </div>
            </div>
        </div>

        {if !empty($message)}
            <div id="message-wrapper" class="container">
                {if !empty($message_type)}
                    {$alert_class = "alert-$message_type"}
                {else}
                    {$alert_class = ""}
                {/if}
                <div class="alert {$alert_class}">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {$message}
                </div>
            </div>
        {/if}
        
{/if}    

        <div id="content" class="container">
            {block name=body}{/block}
        </div>

{if empty($smarty.get.viewmode)} 
        

        <div id="footer-content">
            <div class="container">
                <div class="row">
                    <div class="footer-links span3">
                        {if !empty($static_contents['Footer Links'])}
                            {$static_contents['Footer Links']}
                        {/if}
                    </div>
                    <div class="footer-links span3">
                        {if !empty($static_contents['Footer Links 2'])}
                            {$static_contents['Footer Links 2']}
                        {/if}
                    </div>
                    <div class="footer-links span3">
                        {if !empty($static_contents['Footer Links 3'])}
                            {$static_contents['Footer Links 3']}
                        {/if}
                    </div>
                    <div id="footer-newsletter-signup" class="span3">
                        {if !empty($links)}
                            <h3>{sirclo_get_text text='join_mailing_list_title'}</h3>
                            <div class="input-append sirc-newsletter">
                                <form action="{$links['newsletter']}" method="post">
                                    <input class="span2" id="appendedInputButton" type="email" name="email" placeholder="{sirclo_get_text text='your_email_placeholder'}...">
                                    <button style="" class="btn btn-flat" type="submit">{sirclo_get_text text='misc_submit'}</button>
                                </form>
                            </div>
                        {/if}

                        <div class="social-media">
                            {if !empty($configs.theme_facebook_url)}
                                <a href="{$configs.theme_facebook_url}"><img src="{sirclo_resource file='images/socmed-fb-light.png'}"></a>
                            {/if}

                            {if !empty($configs.theme_twitter_url)}
                                <a href="{$configs.theme_twitter_url}"><img src="{sirclo_resource file='images/socmed-twitter-light.png'}"></a>
                            {/if}

                            {if !empty($configs.theme_instagram_url)}
                                <a href="{$configs.theme_instagram_url}"><img src="{sirclo_resource file='images/socmed-ig-light.png'}"></a>
                            {/if}
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div id="footer-copyright" class="span6">
                        <span>
                            {if !empty($static_contents['Footer Text'])}
                                {$static_contents['Footer Text']}
                            {/if}
                        </span>
                        Powered by <a href="http://www.sirclo.com">SIRCLO</a></span>
                    </div>
                    <div id="footer-payment" class="span6">
                        {if !empty($static_contents['Payment Logo'])}
                            {$static_contents['Payment Logo']}
                        {/if}
                    </div>
                </div>

            </div>
        </div>

        {sirclo_render_js}
        {sirclo_render_ajax_info}
        {$_body_js = 'type="text/javascript"'}
        <script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script>
        <script {$_body_js} src="{sirclo_resource file='js/jquery-migrate.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/jquery.scrollTo.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/cloud-zoom.1.0.3-min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/bootstrap.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/jquery.nivo.slider.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/quickview.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/ccdialog.js'}"></script>
{/if} 
        {block name=footer}{/block}
{if empty($smarty.get.viewmode)}
    </body>
</html>

{/if} 
