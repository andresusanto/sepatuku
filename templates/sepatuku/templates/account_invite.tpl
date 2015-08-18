<!-- 
SEPATUKU SIRCLO THEME 
 (c) 2015 by AMBISNIS.COM
 Enquiry: sales@ambisnis.com
-->

{extends file='includes/theme.tpl'}

{block name="body"}
<!-- PAGE CONTENT HERE -->
	<div class="row s-main-content s-invite-friends">
		<div class="col-md-12 s-breadcrumb s-bottom-padding">
			<a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
			<span>&nbsp;/&nbsp;</span>
			<a href="{$links.account_invite}">Invite Friends</a>
		</div>
		<div class="col-md-12 s-content-title">
			<h1>INVITE FRIENDS</h1>
			<hr/>
		</div>
		<div class="col-md-8 col-md-offset-2 s-top-margin s-content text-center">
			<p class="s-top-margin">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam tincidunt risus et fermentum venenatis. Suspendisse molestie purus sit amet ligula placerat rutrum. Aliquam erat volutpat. Phasellus id finibus sem. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce tempus lectus vel posuere varius. Sed viverra dignissim lorem eu pharetra. Ut ut sagittis nulla. Donec eu ultrices lorem, in rutrum odio. Sed viverra at erat non facilisis. Duis at felis accumsan, mollis sapien eu, elementum est.
			</p>
			<p class="s-top-margin">Enter email address</p>
			<div class="row">
				<textarea class="col-xs-10 col-xs-offset-1 col-md-10 col-md-offset-1 s-bottom-margin" rows="7"></textarea>
			</div>
			<button class="btn btn-lg blue-button">SEND</button>
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

