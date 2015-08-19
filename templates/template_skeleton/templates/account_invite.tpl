{extends file='includes/theme.tpl'}
{block name="body"}

    <div class="container" id="common-page-header">
        <h1>Invite Your Friend</h1>
    </div>

    <div class="container" id="account-content">
        <div class="row sirclo-no-negative col-wrap">
            <div id="account-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar">
                {$_sidename = 'account_invite'}
                {include 'includes/account_nav.tpl'}
            </div>

            <div id="account-form" class="span-sirclo4-3 col">
                <form action="" method="post" class="validasi">
                    <textarea name="email_addresses" placeholder="Enter email address (seperate by comma)"></textarea>
                    <p class="align-center"><input class="btn-flat" type="submit" value="invite" ></p>
                </form>
            </div>
        </div>
    </div>
{/block}

