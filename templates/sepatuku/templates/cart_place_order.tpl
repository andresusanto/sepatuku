{extends file='includes/theme.tpl'}
{block name="body"}
<div class="s-breadcrumb s-bottom-padding">
    <a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
    <span>&nbsp;/&nbsp;</span>
    <a href="{$links.cart_place_order}">{sirclo_get_text text='cart_place_order'}</a>
</div>

<div id="common-page-header" class="col-md-12 s-content-title">
    <h1>{sirclo_get_text text='cart_place_order'}</h1>
    <hr/>
</div>

<div id="cart-place-order" class="container s-checkout-page">
    <div id="cart-place-order-step2" class="row s-top-margin">
        <div class="col-md-6 s-top-margin">
            <div id="cart-place-order-summary" class="wrapper">
                <h2>{sirclo_get_text text='order_summary_title'}</h2>
                <div id="cart-place-order-summary-table">
                    {sirclo_render_cart_table cart=$cart cart_table_mode='mini' with_shipping_country=true show_header=true}
                </div>
                <div id="cart-place-order-summary-loading">
                    <img src="{sirclo_resource file='/images/ajax-loader.gif'}"><br/>{sirclo_get_text text='loading_text'}
                </div>
            </div>
        </div>
        <div class="col-md-6 s-top-margin">
            <div id="cart-place-order-personal" class="wrapper s-half-form">
                <h2>{sirclo_get_text text='cart_place_order_title'}</h2>

                {if !empty($member)}
                    {sirclo_render_place_order_form form_class="sirclo-form shipping-form" member=$member paypal=$paypal shipping_city=$cart['shipping_city'] shipping_country=$cart['shipping_country'] link_terms=$links['terms'] link_privacy=$links['privacy'] label_bank_transfer=$static_contents['Bank Account Info'] btn_class="btn btn-lg blue-button" city_enabled=true _payment_methods=$payment_methods with_shipping_methods=true}
                {else}
                    {sirclo_render_place_order_form form_class="sirclo-form shipping-form" paypal=$paypal shipping_city=$cart['shipping_city'] shipping_country=$cart['shipping_country'] link_terms=$links['terms'] link_privacy=$links['privacy'] label_bank_transfer=$static_contents['Bank Account Info'] btn_class="btn btn-lg blue-button" city_enabled=true _payment_methods=$payment_methods with_shipping_methods=true}
                {/if}
            </div>
        </div>
    </div>
</div>


{/block}

{block name="footer"}
<script type="text/javascript" src="http://cdn.sirclo.com/initareas.js"></script>
<script type="text/javascript" src="http://cdn.sirclo.com/scroll_follow.js"></script>
<script type="text/javascript">
    var place_order = new SIRCLO.CartPlaceOrder();
    place_order.init();
    $(document).ready(function () {
        if ($(window).width() >= 980) {
            $('#cart-place-order-summary').ccScrollFollow({ limiter: '#cart-place-order-personal' });
        }
    });
</script>
{/block}