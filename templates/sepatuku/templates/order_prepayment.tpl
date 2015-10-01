{extends file='includes/theme.tpl'}

{block name="body"}
<div class="container col-md-12 s-content-title s-top-margin" id="common-page-header">
        <h1>Thank you for your order!</h1>
        <hr/>
    </div>

<div id="common-page-content" class="container box-shadow">
    <div id="order-prepayment-content" class="wrapper">
        <p>
            {sirclo_get_text text='redirect_payment'}.<br />
            <strong>{sirclo_get_text text='dont_refresh'}</strong>
            {if $order['payment_method']=="paypal"}
            <span> Click <a href="{$__view.base_url}/orders/{$order.order_id}/paypal">here</a> in case you are not redirected properly.</span>
            {else}
                <span> Click <a href="{$payment_link}">here</a> in case you are not redirected properly.</span>
            {/if}
            </p>
        </p>
        <p><img src="{sirclo_resource file='/images/ajax-loader.gif'}" /></p>
    </div>
</div>
{/block}

{block name="footer"}
    {sirclo_render_ga_ecommerce_script order=$order}
    <script>
      _gaq.push(function () { setTimeout(function () { window.location = "{$payment_link}"; }, 2000);});
      setTimeout(function () { window.location = "{$payment_link}"; }, 5000);
    </script>
{/block}