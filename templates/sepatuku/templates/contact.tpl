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
					<a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
					<span>&nbsp;/&nbsp;</span>
					<a href="{$links.contact}">{sirclo_get_text text='contact_us_title'}</a>
				</div>
				<div class="col-md-12 s-content-title">
					<h1>{sirclo_get_text text='contact_us_title'}</h1>
					<hr/>
				</div>
				<div class="col-md-5 s-contact s-contact-form-container">
					{sirclo_render_contact_form form_class="sirclo-form" action=$links.contact}
				</div>
				
				<div class="col-md-2">
				</div>
				
				<div class="col-md-5 s-contact s-contact-info">
				<span class="title">OUR STORE</span>
				<table>
					<tr>
						<td class="first">P</td>
						<td>0813123123</td>
					</tr>
					<tr>
						<td class="first">E</td>
						<td>email@alamak.com</td>
					</tr>
					<tr>
						<td class="first">A</td>
						<td>Jalan Mangkubumi, Kasunanan, Mangkunegaran</td>
					</tr>
				</table>
				<span class="title">DAILY OPEN</span>
				<div>09.00pm - 05.00am</div>
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
