<!-- 
SEPATUKU SIRCLO THEME 
 (c) 2015 by AMBISNIS.COM
 Enquiry: sales@ambisnis.com
-->

{extends file='includes/theme.tpl'}

{block name="body"}
	<div class="row s-main-content s-lookbook">
		<div class="col-md-12 s-breadcrumb s-bottom-padding">
			<a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
			<span>&nbsp;/&nbsp;</span>
			<a href="{$links.photos}">{sirclo_get_text text='lookbook_title'}</a>
		</div>
		<div class="col-md-12 s-content-title">
			<h1>{sirclo_get_text text='lookbook_title'}</h1>
			<hr/>
		</div>
		
		<div class="col-md-12 s-top-margin span-sirclo4-3 col" id="photos" class="">
			<div class="gamma-container gamma-loading" id="gamma-container">
				<ul class="gamma-gallery">
					{if !empty($photos)}
                        {foreach $photos as $photo}
                            <li>
                            <div data-alt="img03" data-description="<h3>{$photo.title}</h3>" data-max-width="1800" data-max-height="1350">
								{foreach $photo.images as $pi}
									<div data-src="{$pi}"> </div>
								{/foreach}
							</div>
                        {/foreach}
                    {/if}
				</ul>
				<div class="gamma-overlay"></div>
			</div>
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

