{extends file='includes/theme.tpl'}

{block name="body"}

    <div class="container" id="account-content">
		<h1>{sirclo_get_text text='account_details_title'}</h1>
		<div class="text-center" style="font-size: 20px;">You have {if !empty($member.npoints)}{$member.npoints}{else}0{/if} point</div><br/><br/>
		<div class="row sirclo-no-negative col-wrap">
            <div id="account-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar col-md-3 s-top-margin">
				<h3 class="nomargin">MY ACCOUNT</h3>
                {$_sidename = 'account'}
                {include 'includes/account_nav.tpl'}
            </div>

            <div id="account-form" class="span-sirclo4-3 col col-md-9 s-top-margin">
                {sirclo_render_account_info member=$member table_class="table table-no-bordered"}

                <a href="{$links.account_edit}" class="btn btn-lg reg-button s-add-to-cart">{sirclo_get_text text='edit_account_info'}</a>
            </div>
        </div>
    </div>
	<br/><br/>
{/block}
