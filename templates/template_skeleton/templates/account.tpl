{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="container pos" id="common-page-header">
        <h1>{sirclo_get_text text='account_details_title'}<br/><p class="pos">You have {if !empty($member.npoints)}{$member.npoints}{else}0{/if} point</p></h1>
    </div>

    <div class="container" id="account-content">
        <div class="row sirclo-no-negative col-wrap">
            <div id="account-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar">
                {$_sidename = 'account'}
                {include 'includes/account_nav.tpl'}
            </div>

            <div id="account-form" class="span-sirclo4-3 col">
                {sirclo_render_account_info member=$member table_class="table table-no-bordered"}

                <a href="{$links.account_edit}" class="btn-flat">{sirclo_get_text text='edit_account_info'}</a>
            </div>
        </div>
    </div>
{/block}
