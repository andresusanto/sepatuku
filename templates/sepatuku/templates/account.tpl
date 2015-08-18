<!-- 
SEPATUKU SIRCLO THEME 
 (c) 2015 by AMBISNIS.COM
 Enquiry: sales@ambisnis.com
-->

{extends file='includes/theme.tpl'}

{block name="body"}
    <!-- PAGE CONTENT HERE -->
		<div class="row s-main-content s-account-page">
			<div class="col-md-12 s-breadcrumb s-bottom-padding">
				<a href="{$links['home']}">{sirclo_get_text text='home_title'}</a>
				<span>&nbsp;/&nbsp;</span>
				<a href="{$links['account']}">{sirclo_get_text text='my_account_link'}</a>
				<span>&nbsp;/&nbsp;</span>
				<a href="{$links['account']}">{sirclo_get_text text='account_info_link'}</a>
			</div>
			<div class="col-md-12 s-content-title">
				<h1>{sirclo_get_text text='account_info_title'}</h1>
				<hr/>
			</div>
			<div class="container" id="account-content">
				<div class="row sirclo-no-negative col-wrap">
					<div id="account-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar">
						{$_sidename = 'account'}
						{include 'includes/account_nav.tpl'}
					</div>

					<div id="account-form" class="span-sirclo4-3 col">
						{sirclo_render_account_info member=$member table_class="table table-no-bordered"}
					</div>
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
