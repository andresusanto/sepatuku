{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="container col-md-12 s-content-title s-top-margin" id="common-page-header">
        <h1>{sirclo_get_text text='change_password_title'}</h1>
        <hr/>
    </div>

    <div class="container" id="account-content">
        <div class="row sirclo-no-negative col-wrap">
            <div id="account-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar col-md-3 s-top-margin">
                <h3 class="nomargin">MY ACCOUNT</h3>
                {$_sidename = 'account_edit_password'}
                {include 'includes/account_nav.tpl'}
            </div>

            <div id="account-form" class="span-sirclo4-3 col col-md-9 s-top-margin">
                {sirclo_render_account_edit_password form_class="sirclo-form"}
            </div>
        </div>
    </div>
{/block}

{block name="footer"}
    <script type="text/javascript">
        $('.sirclo-form').validate({
            rules : {
                confirm_new_password : {
                    equalTo : "#input_new_password"
                }
            },
            messages : {
                confirm_new_password : {
                    equalTo : "Please input the same password as above"
                }
            }
        });
    </script>
{/block}
