{extends file='includes/theme.tpl'}

{block name="body"}
<!-- tidak bisa diberi breadcrumb -->
    <div class="container col-md-12 s-content-title s-top-margin" id="common-page-header">
        <h1>{sirclo_get_text text='order_history_title'}</h1>
        <hr/>
    </div>

    <div class="container" id="account-content">
        <div class="row sirclo-no-negative col-wrap">
            <div id="account-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar col-md-3 s-top-margin">
                <h2 class="nomargin">MY ACCOUNT</h2>
                {$_sidename = 'account'}
                {include 'includes/account_nav.tpl'}
            </div>

            <div id="account-form" class="span-sirclo4-3 col col-md-9 s-top-margin">
                {if !empty($orders)}
                    {sirclo_render_account_order_list table_class="table" orders=$orders links_account=$links['account'] currency=$active_currency}
                {else}
                    <p>{sirclo_get_text text='dont_have_any_orders'}.</p>
                {/if}
                <div id="orders-list-paging" class="span-sirclo4-2">
                    {if !empty($paging)}
                        {sirclo_render_pagination paging=$paging view_all=""}
                    {/if}
                </div>
            </div>
        </div>
    </div>
{/block}

{block name="footer"}
    <script type="text/javascript">
        $('.sirclo-form').validate();
    </script>
{/block}
