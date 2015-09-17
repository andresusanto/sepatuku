<!-- 
SEPATUKU SIRCLO THEME 
 (c) 2015 by AMBISNIS.COM
 Enquiry: sales@ambisnis.com
-->

{extends file='includes/theme.tpl'}

{block name="body"}
	<!-- PAGE CONTENT HERE -->
	<div class="row s-main-content">
		<div class="col-md-12 s-breadcrumb s-bottom-padding">
			<a href="{$links['home']}">{sirclo_get_text text='home_title'}</a>
			<span>&nbsp;/&nbsp;</span>
			<a href="{$links['account_login']}">{sirclo_get_text text='login_title'}</a>
		</div>
		<div class="col-md-12 s-content-title">
			<h1>{sirclo_get_text text='login_title'}</h1>
			<hr/>
		</div>
		<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 s-login-container">
			<div class="s-description s-bottom-margin">
				{sirclo_get_text text='login_welcome'}
			</div>
			
			<!-- LOGIN FORM -->
			{sirclo_render_login_form form_class="sirclo-form" action=$context_links['account_login']}
			<!-- -->
			
			<a class="s-bottom-margin" href="{$links['account_reset_password']}">{sirclo_get_text text='forgot_password_link'}</a>
			
			<div class="col-md-12 s-content-title s-top-margin s-top-padding">
				<h1>{sirclo_get_text text='register_title'}</h1>
				<hr/>
			</div>
			
			<a class="btn btn-lg blue-button s-fullwidth s-bottom-margin s-top-margin s-bebas" href="{$context_links['account_register']}">{sirclo_get_text text='login_register_link'}</a>
			<div>{sirclo_get_text text='receive_member_privileges'}</div>
			
			{if empty($member)}
				<div class="s-strike s-top-margin">
					<span>{sirclo_get_text text='misc_or'}</span>
				</div>
				<a id="link-checkout-step2" class="btn btn-lg blue-button s-fullwidth s-bottom-margin s-top-margin s-checkout-as-guest s-bebas" href="{$guest_checkout_link}">{sirclo_get_text text='guest_checkout_title'}</a>
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
