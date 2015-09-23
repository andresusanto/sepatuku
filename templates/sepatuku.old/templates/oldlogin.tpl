{extends file='includes/theme.tpl'}

{block name="body"}    
    <div id="common-page-header">
        <h1>{sirclo_get_text text='login_title'}</h1>
        <p>{sirclo_get_text text='login_existing_account_title'}</p>
    </div>

    <div id="common-page-content">
        <div id="store-login" class="row">
            <div class="span6">
                <div class="wrapper">
                    <h2>{sirclo_get_text text='login_title'}</h2>
                    {sirclo_render_login_form form_class="sirclo-form" action=$context_links['account_login']}
                    <div class="footer-div">
                        <span>{sirclo_get_text text='forgot_password_link'} <a href="{$links['account_reset_password']}">{sirclo_get_text text='here_link'}</a></span>
                    </div>
                </div>
            </div>

            <div class="span6">
                <div class="wrapper">
                    <h2>{sirclo_get_text text='register_title'}</h2>
                    <div class="footer-div">
                        <span>{sirclo_get_text text='register_dont_have_account_yet'}</span>
                        <a class="btn-flat" href="{$context_links['account_register']}">{sirclo_get_text text='login_register_link'}</a>
                    </div>
                    {if !empty($configs.theme_facebook_appid)}
                    <div class="hr-wrap"><hr>
                    <span class="social-or">{sirclo_get_text text='misc_or'}</span>
                    </div>
                    <div class="social-wrap">
                        <button id="fbloginlink" href="javascript:void(0)">Login with Facebook</button>
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

