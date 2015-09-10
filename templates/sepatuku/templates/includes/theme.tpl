<!-- 
SEPATUKU SIRCLO THEME 
 (c) 2015 by AMBISNIS.COM
 Enquiry: sales@ambisnis.com
-->
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
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        {if !empty($meta)}
            {foreach $meta as $meta_content}
                <meta name="{$meta_content@key}" content="{$meta_content}">
            {/foreach}
        {/if}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {if !empty($title)}<meta property="og:title" content="{$title}" />{/if}
        <meta property="og:url" content="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" />
        {if !empty($product.images)}<meta property="og:image" content="{$product.images['0']}" />{/if}
        {if !empty($configs.theme_favicon_url)}
            <link rel="icon" type="image/png" href="{$configs.theme_favicon_url}"/>
        {/if}

        <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>

        {$_body_css = 'rel="stylesheet" type="text/css"'}
        <!-- Bootstrap -->
        <link {$_body_css} href="{sirclo_resource file='css/bootstrap.min.css'}">

        <!-- Sepatuku CSS-->
        <link {$_body_css} href="{sirclo_resource file='css/drawer.css'}">
        <link {$_body_css} href="{sirclo_resource file='css/jquery.bxslider.css'}">
        <link {$_body_css} href="{sirclo_resource file='css/sepatuku.css'}">
        <link {$_body_css} href="{sirclo_resource file='css/font-awesome.css'}">
        <link {$_body_css} href="{sirclo_resource file='css/lookbook.css'}">
        <script src="{sirclo_resource file='js/modernizr.custom.70736.js'}"></script>
        
        <!-- {if !empty($configs.theme_background_image)}{literal}<style>body{background: url('{/literal}{$configs.theme_background_image}{literal}') repeat;} </style>{/literal}{/if}
        {block name=header}{/block}
    
        {if !empty($static_contents['Scripts'])}
        {$static_contents['Scripts']|regex_replace:"/(<p>|<p [^>]*>|<\\/p>)/":""}
        {/if} -->
    </head>

    <body class="drawer drawer-left">
        <header role="banner">
            <div class="drawer-main drawer-default">
                <nav class="drawer-nav" role="navigation">
                    <br/><br/>
                    <ul class="drawer-menu">
                        {if !empty($main_nav)}
                            {call skeleton_render_main_navbar nav_links=$main_nav pos=mobile}
                        {/if}
                    </ul>
                    <div class="drawer-footer"><span></span></div>
                </nav>
            </div>
        </header>

        <div class="drawer-overlay">
            <div class="container">
                <!-- BEGIN HEADER -->

                <!-- MENU KECIL YANG ADA DIATAS -->
                <div class="row">
                    <div class="s-menu-kecil pull-right">
                        {if !empty($links)}
                            <ul class="list-unstyled">
                                {if empty($member)}
                                    <li>
                                        <a class="page-scroll" href="{$links.account_login}" />{sirclo_get_text text='login_link'}</a>
                                    </li>
                                    <li>
                                        <a class="page-scroll" href="{$links.account_register}" />{sirclo_get_text text='register_link'}</a>
                                    </li>
                                {else}
                                    <li>
                                        <a class="page-scroll" href="{$links.account}" />{sirclo_get_text text='account_info_link'}</a>
                                    </li>
                                {/if}
                                <li class="dropdown">
                                    <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="{sirclo_resource file='images/ico-bag.png'}" />
                                        {if !empty($cart) and !empty($cart.grand_total)}
                                            ({$cart.total_items})
                                        {else}
                                            (0)
                                        {/if}
                                     Item(s)</a>
                                    <ul class="dropdown-menu s-chart-top">
                                      <h4>{sirclo_get_text text='shopping_cart'}</h4>
                                      {if !empty($cart.items)}
                                        {foreach $cart.items as $sc}
                                            {$sc.quantity} x {$sc.name}<br/>
                                        {/foreach}
                                        <div class="s-checkout-right"><a href="{$links.cart}"></i>{sirclo_get_text text='checkout'}</a></div>
                                      {/if}
                                      <br/>
                                    </ul>
                                </li>
                            </ul>
                        {/if}
                    </div>
                </div>
                <!-- END MENU KECIL -->
            
                <!-- MENU DAN LOGO ATAS -->
                <div class="row">
                    <!-- LEFT MENU -->
                    <div class="col-md-5 s-menu-left s-menu-utama">
                        <div class="s-web-content">
                            <div class="row">
                                {if !empty($main_nav)}
                                    {call skeleton_render_main_navbar nav_links=$main_nav pos=left}
                                {/if}
                            </div>
                        </div>
                        <div class="s-mobile-content s-top-search">
                            <input class="form-control" type="text" placeholder="Search">
                        </div>
                    </div>
                    <!-- LOGO UTAMA SITUS -->
                    <div class="col-md-2 s-logo-utama">
                    {if isset($main_nav.home.link) and isset($logo_url)}
                        <a href="{$main_nav.home.link}"><img src="{sirclo_resource file=$logo_url}" /></div></a>
                    {else if isset($logo_url)}
                        <a href="#"><img src="{sirclo_resource file=$logo_url}" /></div></a>
                    {else}
                        <a href="#"></div></a>
                    {/if}

                    <!-- RIGHT MENU -->
                    <div class="col-md-5 s-menu-right s-menu-utama">
                        <div class="s-web-content">
                            <div class="row">
                                {if !empty($main_nav)}
                                    {call skeleton_render_main_navbar nav_links=$main_nav pos=right}
                                {/if}
                            </div>
                        </div>
                        <div class="s-mobile-content">
                            <div class="s-mobile-menu">
                                <a href="javascript:void(0);" id="menu-mobile"><img src="{sirclo_resource file='images/ico-menu.png'}"/>{sirclo_get_text text='menu'}</a>
                            </div>
                        </div>  
                    </div>
                </div>
                <!-- END LOGO DAN MENU ATAS -->
                
                <!-- END HEADER -->

                <!-- ALERT BOX-->
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

                {block name=body}{/block}

        {if empty($smarty.get.viewmode)}
                <!-- BEGIN FOOTER -->

                <!-- FOOTER MENU -->

                <div id="footer-content"
                <div id="footer-content row s-footer-menu">
                    <div class="container">
                        <div class="footer-links span3 col-md-3">
                            {if !empty($static_contents['Footer Links'])}        
                                {$static_contents['Footer Links']}
                            {/if}
                        </div>
                        <div class="footer-links span3 col-md-3">
                            {if !empty($static_contents['Footer Links 2'])}
                                {$static_contents['Footer Links 2']}
                            {/if}
                        </div>
                        <div class="footer-links span3 col-md-3">
                            {if !empty($static_contents['Footer Links 3'])}
                                {$static_contents['Footer Links 3']}
                            {/if}
                        </div>

                        <div class="col-md-3">
                            <h4>{sirclo_get_text text='contact'}</h4>
                            <i class='fa fa-phone'></i> (021) 555 7777<br/><i class='fa fa-clock-o'></i> 09:00 AM - 06.00 PM (WIB)<br/>
                            
                            <h4>{sirclo_get_text text='newsletter'}</h4>
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="{sirclo_get_text text='email_address'}">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button">{sirclo_get_text text='subscribe'}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                </div>
                
                <div class="s-footer-bleky">
                    <div class="container">
                        <!-- FOOTER SOCIAL -->
                        <div class="row s-foot-social">
                            <a href="{$configs.theme_facebook_url}"><img src="{sirclo_resource file='images/ico-fb.png'}"/></a>
                            <a href="{$configs.theme_twitter_url}"><img src="{sirclo_resource file='images/ico-twitter.png'}"/></a>
                            <a><img src="{sirclo_resource file='images/logo-bawah.png'}"/></a>
                            <a href="{$configs.theme_pinterest_url}"><img src="{sirclo_resource file='images/ico-pintrest.png'}"/></a>
                            <a href="{$configs.theme_instagram_url}"><img src="{sirclo_resource file='images/ico-instagram.png'}"/></a>
                        </div>
                        <!-- FOOTER COPYRIGHT -->
                        <div class="row s-foot-copyright">
                            {$static_contents['Footer Text']}
                        </div>
                        <!-- FOOTER EXTRA MENU -->
                        <div class="row s-foot-menu-end">
                            <ul class="list-unstyled">
                                <li>
                                    <a class="page-scroll" href="{$links.about}">{sirclo_get_text text='about'}</a>
                                </li>
                                <li>
                                    <a class="page-scroll" href="{$links.contact}">{sirclo_get_text text='contact'}</a>
                                </li>
                                <li>
                                    <a class="page-scroll" href="{$links.terms}">{sirclo_get_text text='terms'}</a>
                                </li>
                                <li>
                                    <a class="page-scroll" href="{$links.privacy}">{sirclo_get_text text='privacy'}</a>
                                </li>
                                <li>
                                    <a class="page-scroll" href="{$links.sitemap}">{sirclo_get_text text='sitemap'}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- END FOOTER-->

        {$_body_js = 'type="text/javascript"'}
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.1/iscroll-min.js"></script>

        <script {$_body_js} src="{sirclo_resource file='js/jquery.drawer.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/bootstrap.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/jquery.elevatezoom.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/sepatuku.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/jquery.masonry.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/jquery.history.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/js-url.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/jquerypp.custom.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/gamma.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/jquery.bxslider.min.js'}"></script>
{/if}
        {block name=footer}{/block}
{if empty($smarty.get.viewmode)}
    </body>
</html>

{/if} 
