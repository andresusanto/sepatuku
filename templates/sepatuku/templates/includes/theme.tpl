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
        <link {$_body_css} href="{sirclo_resource file='css/font-awesome.min.css'}">
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
                        {foreach $main_nav as $key=>$nav}
                            {if $key != 'home'}
                                {if isset($nav.sub_nav)}
                                    <li class="drawer-menu-item dropdown drawer-dropdown">
                                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false">{$nav.title}<span class="caret"></span></a>
                                        <ul class="drawer-submenu dropdown-menu" role="menu">
                                            {foreach $nav.sub_nav as $sn}
                                                <li class="drawer-submenu-item"><a href="{$sn.link}">{$sn.title}</a></li>
                                            {/foreach}
                                        </ul>
                                    </li>
                                {else}
                                    <li class="drawer-menu-item"><a href="{$nav.link}">{$nav.title}</a></li>
                                {/if}
                            {/if}
                        {/foreach}
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
                                    <li>
                                        <a class="page-scroll" href="{$links.account_info}" />{sirclo_get_text text='account_info'}</a>
                                    </li>
                                {/if}
                                <li class="dropdown">
                                    <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="/images/ico-bag.png" />
                                        {if !empty($cart) and !empty($cart.grand_total)}
                                            ({$cart.total_items})
                                        {else}
                                            (0)
                                        {/if}
                                     Item(s)</a>
                                    <ul class="dropdown-menu s-chart-top">
                                      <h4>{sirclo_get_text text='shopping_cart'}</h4>
                                      {if !empty($shopping_cart)}
                                        {foreach $shopping_cart as $sc}
                                            {$sc.count} x {$sc.name}<br/>
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
                                {$i = 0}
                                {foreach $main_nav as $key=>$nav}
                                    {if $key != 'home'}
                                        {if $i lt (count($main_nav)-1)/2}
                                            {if isset($nav.sub_nav)}
                                                <div class="col-md-{(int) (24/(count($main_nav)-1))|floor}">
                                                    <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                        <div class="s-menu-text-big">{$nav.title}</div>
                                                        <div class="s-menu-text-small">{sirclo_get_text text=$nav.title|cat:'_collections'|replace:' ':'_'|lower}</div>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        {foreach $nav.sub_nav as $sn}
                                                            <li><a href="{$sn.link}">{$sn.title}</a></li>
                                                        {/foreach}
                                                    </ul>
                                                </div>
                                            {else}
                                                <div class="col-md-{(int) (24/(count($main_nav)-1))|floor}">
                                                    <a href="{$nav.link}">
                                                        <div class="s-menu-text-big">{$nav.title}</div>
                                                        <div class="s-menu-text-small">{sirclo_get_text text=$nav.title|cat:'_collections'|replace:' ':'_'|lower}</div>
                                                    </a>
                                                </div>
                                            {/if}
                                        {/if}
                                        {$i=$i+1}
                                    {/if}
                                {/foreach}
                            </div>
                        </div>
                        <div class="s-mobile-content s-top-search">
                            <input class="form-control" type="text" placeholder="Search">
                        </div>
                    </div>
                    <!-- LOGO UTAMA SITUS -->
                    <div class="col-md-2 s-logo-utama">
                    {if !empty($logo_url)}
                        <a href="{$main_nav.home.link}"><img src="{$logo_url}" /></div></a>
                    {/if}

                    <!-- RIGHT MENU -->
                    <div class="col-md-5 s-menu-right s-menu-utama">
                        <div class="s-web-content">
                            <div class="row">
                                {$i = 0}
                                {foreach $main_nav as $key=>$nav}
                                    {if $key != 'home'}
                                        {if $i gte (count($main_nav)-1)/2}
                                            {if isset($nav.sub_nav)}
                                                <div class="col-md-{(int) (24/(count($main_nav)-1))|ceil}">
                                                    <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                        <div class="s-menu-text-big">{$nav.title}</div>
                                                        <div class="s-menu-text-small">{sirclo_get_text text=$nav.title|cat:'_collections'|replace:' ':'_'|lower}</div>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        {foreach $nav.sub_nav as $sn}
                                                            <li><a href="{$sn.link}">{$sn.title}</a></li>
                                                        {/foreach}
                                                    </ul>
                                                </div>
                                            {else}
                                                <div class="col-md-{(int) (24/(count($main_nav)-1))|ceil}">
                                                    <a href="{$nav.link}">
                                                        <div class="s-menu-text-big">{$nav.title}</div>
                                                        <div class="s-menu-text-small">{sirclo_get_text text=$nav.title|cat:'_collections'|replace:' ':'_'|lower}</div>
                                                    </a>
                                                </div>
                                            {/if}
                                        {/if}
                                        {$i=$i+1}
                                    {/if}
                                {/foreach}
                            </div>
                        </div>
                        <div class="s-mobile-content">
                            <div class="s-mobile-menu">
                                <a href="javascript:void(0);" id="menu-mobile"><img src="/images/ico-menu.png"/>{sirclo_get_text text='menu'}</a>
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
                <div class="row s-footer-menu">
                    <div class="col-md-3">
                        <h4>TOP LABELS</h4>
                        <ul class="list-unstyled">
                            <li>
                                <a class="page-scroll" href="#">Sacha Drake</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">George</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Joseph Ribkoff</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">State of Georgia</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Unspoken</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">7 Camicie</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Maurie & Eve</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Cannisse</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h4>CATEGORIES</h4>
                        <ul class="list-unstyled">
                            <li>
                                <a class="page-scroll" href="#">Casual Shoes</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Running Shoes</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Sport Shoes</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Football Shoes</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Sandals</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Slippers</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Sneakers</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h4>LABELS</h4>
                        <ul class="list-unstyled">
                            <li>
                                <a class="page-scroll" href="#">Anna Kendrick</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Robert Downey Jr.</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="#">Leonardo Dicaprio</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h4>CONTACT</h4>
                        <i class="fa fa-phone"></i> (021) 555 7777<br/>
                        <i class="fa fa-clock-o"></i> 09:00 AM - 06.00 PM (WIB)<br/>
                        
                        <h4>NEWSLETTER</h4>
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Email Address">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button">{sirclo_get_text text='subscribe'}</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="s-footer-bleky">
                <div class="container">
                    <!-- FOOTER SOCIAL -->
                    <div class="row s-foot-social">
                        <a href="{$configs.theme_facebook_url}"><img src="/images/ico-fb.png"/></a>
                        <a href="{$configs.theme_twitter_url}"><img src="/images/ico-twitter.png"/></a>
                        <a><img src="/images/logo-bawah.png"/></a>
                        <a href="{$configs.theme_pinterest_url}"><img src="/images/ico-pintrest.png"/></a>
                        <a href="{$configs.theme_instagram_url}"><img src="/images/ico-instagram.png"/></a>
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
                            <li>
                                <a class="page-scroll" href="{$links.lookbook}">{sirclo_get_text text='lookbook'}</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="{$links.blog}">{sirclo_get_text text='blog'}</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="{$links.testimonials}">{sirclo_get_text text='testimonial'}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- END FOOTER-->
        </div>

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
