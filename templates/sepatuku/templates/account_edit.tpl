{extends file='includes/theme.tpl'}

{block name="body"}
<!-- tidak bisa diberi breadcrumb -->
    <div class="container col-md-12 s-content-title s-top-margin" id="common-page-header">
        <h1>{sirclo_get_text text='edit_account_info'}</h1>
        <hr/>
    </div>

    <div class="container" id="account-content">
        <div class="row sirclo-no-negative col-wrap">
            <div id="account-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar col-md-3 s-top-margin">
                <h3 class="nomargin">MY ACCOUNT</h3>
                {$_sidename = 'account'}
                {include 'includes/account_nav.tpl'}
            </div>

            <div id="account-form" class="span-sirclo4-3 col col-md-9 s-top-margin">
                {sirclo_render_account_edit_form form_class="sirclo-form" member=$member with_birthday=true}
            </div>
        </div>
    </div>
{/block}

{block name="footer"}
    <script type="text/javascript" src="http://cdn.sirclo.com/initareas.js"></script>
    <script type="text/javascript">
        var account_edit = new SIRCLO.AccountEdit();
        account_edit.init();
    </script>
{/block}
