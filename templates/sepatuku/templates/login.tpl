{extends file='includes/theme.tpl'}

{block name="body"}    
    <div class="s-breadcrumb s-bottom-padding">
        <a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
        <span>&nbsp;/&nbsp;</span>
        <a href="{$links.login}">{sirclo_get_text text='login'}</a>
    </div>

    <div id="common-page-content">
        <div id="store-login" class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 s-login-container">
                <div class="wrapper">
                    <div id="common-page-header" class="col-md-12 s-content-title s-bottom-margin">
                        <h1>{sirclo_get_text text='login_title'}</h1>
                        <hr/>
                    </div>
                    <div class="s-bottom-margin">Welcome back, please login to manage your account</div>
                    {sirclo_render_login_form form_class="sirclo-form" action=$context_links['account_login']}
                    <div class="footer-div s-bottom-margin">
                        <span>{sirclo_get_text text='forgot_password_link'} <a href="{$links['account_reset_password']}">{sirclo_get_text text='here_link'}</a></span>
                    </div>
                    <div id="common-page-header" class="col-md-12 s-content-title s-top-margin s-bottom-margin">
                        <h1>{sirclo_get_text text='register_title'}</h1>
                        <hr/>
                    </div>
                    <div class="footer-div">
                        <a class="btn btn-lg blue-button s-fullwidth" href="{$context_links['account_register']}">{sirclo_get_text text='login_register_link'}</a>
                    </div>
                    <div class="s-bottom-margin s-top-margin">now to receive exclusive member privilage</div>
                    {if !empty($configs.theme_facebook_appid)}
                    
                    <div class="s-strike s-top-margin">
                        <span>{sirclo_get_text text='misc_or'}</span>
                    </div>
                    </div>
                    <div class="social-wrap">
                        <button id="fbloginlink" href="javascript:void(0)" style="width:100%">Login with Facebook</button>
                    </div>
                 {/if}
                </div>
            </div>
        </div>

        {if !empty($guest_checkout_link)}
            <div id="cart-place-order-guest" class="row">
                <div id="cart-place-order-guest-inner" class="span12">
                    <hr/>
                    {sirclo_get_text text='or_you_can_also'}
                    <a class="btn-flat" id="link-checkout-step2" href="{$guest_checkout_link}">
                        {sirclo_get_text text='guest_checkout_title'}
                    </a>
                </div>
            </div>
        {/if}
    </div>

{/block}

{block name="footer"}
    <script type="text/javascript">
        $('.sirclo-form').validate();
        $('.s-login-container input[type=submit]').addClass("blue-button").addClass("btn-lg");
        $('#input_username').attr('placeholder', "Email");
        $('#input_password').attr('placeholder', "Password");
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

