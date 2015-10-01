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

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:title" content="{$title}" />
        <meta property="og:url" content="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" />
        {if !empty($product.images)}<meta property="og:image" content="{$product.images['0']}" />{/if}
        {if !empty($configs.theme_favicon_url)}
            <link rel="icon" type="image/png" href="{$configs.theme_favicon_url}"/>
        {/if}

        {sirclo_render_css}
        {$_body_css = 'rel="stylesheet" type="text/css"'}
         <link {$_body_css} href="{sirclo_resource file='css/drawer.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/jquery.bxslider.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/bootstrap.min.css'}">
         <link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/flick/jquery-ui.min.css">
         <link {$_body_css} href="{sirclo_resource file='css/ccdialog.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/widget.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/quickview.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/sepatuku.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/font-awesome.min.css'}">
         <link {$_body_css} href="{sirclo_resource file='css/lookbook.css'}">
        <!--{if !empty($configs.theme_background_image)}{literal}<style>body{background: url('{/literal}{$configs.theme_background_image}{literal}') repeat;} </style>{/literal}{/if}-->
        {block name=header}{/block}
		
		<script src="{sirclo_resource file='js/modernizr.custom.70736.js'}"></script>
		
        {if !empty($static_contents['Scripts'])}
			{$static_contents['Scripts']|regex_replace:"/(<p>|<p [^>]*>|<\\/p>)/":""}
		{/if}
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
				<!-- MENU KECIL YANG ADA DIATAS -->
				<div class="row">
					<div class="s-menu-kecil pull-right">
						<ul class="list-unstyled">
							<li>
								<a class="page-scroll" href="{$links.payment_notif}" /> {sirclo_get_text text='confirm_payment_link'}</a>
							</li>
							{if !empty($member)}
								<li>
									<a class="page-scroll" href="{$links.account}" /> {sirclo_get_text text='my_account_link'}</a>
								</li>
								<li>
									<a class="page-scroll" href="{$links.account_logout}" /> {sirclo_get_text text='logout_link'}</a>
								</li>
							{else}
								<li>
									<a class="page-scroll" href="{$links.account_register}" /> {sirclo_get_text text='register_link'}</a>
								</li>
								<li>
									<a class="page-scroll" href="{$links.account_login}" /> {sirclo_get_text text='login_link'}</a>
								</li>
							{/if}
							<li>
								<a href="{$links.cart}"><img src="{sirclo_resource file='images/ico-bag.png'}" /> {if !empty($cart) and !empty($cart.grand_total)}
                                        {call skeleton_pluralize singular="Item" plural="Items" count=$cart.total_items}
                                    {else}
                                        {call skeleton_pluralize singular="Item" plural="Items" count=0}
                                    {/if} </a>
							</li>
						</ul>
					</div>
				</div>
				<!-- END MENU KECIL -->
				
				<!-- MENU DAN LOGO ATAS -->
				<div class="row s-bottom-margin">
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
							{if !empty($links)}
								<form id="search-form" action="{$links.products_search}">
									<input name="query" type="text" class="form-control" placeholder="Search" value="">
									<!--<button type="submit" class="btn-flat">{sirclo_get_text text='misc_search'}</button>-->
								</form>
							{/if}
						</div>
					</div>
					<!-- LOGO UTAMA SITUS -->
					<div class="col-md-2 s-logo-utama">
						{if !empty($logo_url)}
							<a href="/"><img src="{$logo_url}"></a>
						{/if}
					</div>
					
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
				
				{if !empty($message)}
					<div class="row">
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
					</div>
				{/if}
				
				
				{block name=body}{/block}
				
				
				<!-- BEGIN FOOTER -->
				
				<!-- FOOTER MENU -->
				<div class="row s-footer-menu s-top-margin">
                    <div class="container">
                        <div class="col-md-3">
                            {if !empty($static_contents['Footer Links'])}        
                                {$static_contents['Footer Links']}
                            {/if}
                        </div>
                        <div class="col-md-3">
                            {if !empty($static_contents['Footer Links 2'])}
                                {$static_contents['Footer Links 2']}
                            {/if}
                        </div>
                        <div class="col-md-3">
                            {if !empty($static_contents['Footer Links 3'])}
                                {$static_contents['Footer Links 3']}
                            {/if}
                        </div>
                        <div class="col-md-3">
                            {if !empty($static_contents['Footer Links 4'])}
                                {$static_contents['Footer Links 4']}
                            {/if}
                            
							{if !empty($links)}
								<h4>{sirclo_get_text text='join_mailing_list_title'}</h4>
								<div class="input-append sirc-newsletter">
									<form action="{$links['newsletter']}" method="post">
										<div class="input-group">
											<input class="form-control" type="text" id="appendedInputButton" type="email" name="email" placeholder="{sirclo_get_text text='your_email_placeholder'}...">
											<span class="input-group-btn">
												<button class="btn btn-primary" type="submit">{sirclo_get_text text='subscribe'}</button>
											</span>
										</div>
									</form>
								</div>
							{/if}
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
                            {if !empty($static_contents['Widget 1'])}        
                                {$static_contents['Widget 1']}
                            {/if}
                            {if !empty($static_contents['Widget 2'])}        
                                {$static_contents['Widget 2']}
                            {/if}
                            {if !empty($static_contents['Widget 3'])}        
                                {$static_contents['Widget 3']}
                            {/if}
                            {if !empty($static_contents['Widget 4'])}        
                                {$static_contents['Widget 4']}
                            {/if}
                            {if !empty($static_contents['Widget 5'])}        
                                {$static_contents['Widget 5']}
                            {/if}
                        </ul>


                        Powered by <a href="http://www.sirclo.com">SIRCLO</a></span>
                    </div>
                </div>
            </div>
		</div>

        <!--

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
        </div>-->

        {sirclo_render_js}
        {sirclo_render_ajax_info}
        {$_body_js = 'type="text/javascript"'}
        <script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.1/iscroll-min.js"></script>
        <script {$_body_js} src="{sirclo_resource file='js/jquery-migrate.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/jquery.scrollTo.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/cloud-zoom.1.0.3-min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/bootstrap.min.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/quickview.js'}"></script>
        <script {$_body_js} src="{sirclo_resource file='js/ccdialog.js'}"></script>

		<script {$_body_js} src="{sirclo_resource file='js/jquery.drawer.min.js'}"></script>
		<script {$_body_js} src="{sirclo_resource file='js/jquery.elevatezoom.js'}"></script>
		<script {$_body_js} src="{sirclo_resource file='js/sepatuku.js'}"></script>
		<script {$_body_js} src="{sirclo_resource file='js/jquery.masonry.min.js'}"></script>
		<script {$_body_js} src="{sirclo_resource file='js/jquery.history.js'}"></script>
		<script {$_body_js} src="{sirclo_resource file='js/js-url.min.js'}"></script>
		<script {$_body_js} src="{sirclo_resource file='js/jquerypp.custom.js'}"></script>
		<script {$_body_js} src="{sirclo_resource file='js/gamma.js'}"></script>
		<script {$_body_js} src="{sirclo_resource file='js/jquery.bxslider.min.js'}"></script>
        {block name=footer}{/block}
    </body>
</html>

{/if} 
