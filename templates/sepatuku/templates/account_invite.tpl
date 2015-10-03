{extends file='includes/theme.tpl'}
{block name="body"}

    <div class="container col-md-12 s-content-title s-top-margin" id="common-page-header">
        <h1>Invite Your Friend</h1>
        <hr/>
    </div>

    <div class="container" id="account-content">
        <div class="row sirclo-no-negative col-wrap">
            <div id="account-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar col-md-3 s-top-margin">
                <h3 class="nomargin">MY ACCOUNT</h3>
                {$_sidename = 'account_invite'}
                {include 'includes/account_nav.tpl'}
            </div>

            <div id="account-form" class="span-sirclo4-3 col col-md-9 s-top-margin">
                <form action="" method="post" class="validasi">
                    <div class="row">
                        <textarea name="email_addresses" placeholder="Enter email address (seperate by comma)" class="col-xs-12 col-md-10 col-md-offset-1 s-bottom-margin" rows="7"></textarea>
                    </div>
                    <input class="btn-flat btn blue-button col-xs-12 col-md-3 col-md-offset-2" type="submit" value="SEND" >
                </form>
            </div>
        </div>
    </div>
{/block}

