{extends file='includes/theme.tpl'}

{block name="body"}    
	<div id="common-page-header">
        <h1>{sirclo_get_text text='register_title'}</h1>
        <p>{sirclo_get_text text='register_subtitle'}</p>
    </div>

    <div id="common-page-content">
        <div id="store-login" class="row">
            <div class="span6">
                <div class="wrapper">
                    {sirclo_render_register_form form_class="sirclo-form" link_terms=$links['terms'] link_privacy=$links['privacy'] with_birthday=true}
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


