{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="container" id="common-page-header">
        <h1>{sirclo_get_text text='order_history_title'}</h1>
    </div>

    <div class="container" id="account-content">
        <div class="row sirclo-no-negative col-wrap">
            <div id="account-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar">
                {$_sidename = 'order_list'}
                {include 'includes/account_nav.tpl'}
            </div>

            <div id="account-form" class="span-sirclo4-3 col">
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
