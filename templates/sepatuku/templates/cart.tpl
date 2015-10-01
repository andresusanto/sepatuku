{extends file='includes/theme.tpl'}

{block name="body"}

<div class="s-breadcrumb s-bottom-padding">
    <a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
    <span>&nbsp;/&nbsp;</span>
    <a href="{$links.cart}">{sirclo_get_text text='cart_title'}</a>
</div>

<div id="common-page-header" class="col-md-12 s-content-title">
    <h1>Cart</h1>
    <hr/>
</div>

<p>{sirclo_get_text text='review_your_items_title'}</p>

<div id="cart-content" class="row container box-shadow s-cart">

    {if !empty($cart['items'])}
        <div class="cart-table">
            {sirclo_render_cart_table cart=$cart shows_discount=false with_shipping_country=true thumbnail_suffix='_tn' with_backorder_shipping=false with_image=true}
                            {if !empty($cart.total_items)}
                                {sirclo_render_cart_edit_form cart=$cart with_shipping=false with_points=true member=$member points_label='Reward Points to use (You have {max} points)'}
                            {/if}
        </div>
    {else}
        <p class="cart-empty">{sirclo_get_text text='cart_empty'}.</p>
    {/if}

    <div class="s-wavy-separator"></div>

    <div id="cart-continue row">
        <div id="cart-continue-continue">
            <div class="col-md-6 nopadding">
                <a class="continue" href="{$continue_url}"><span class="s-large-font s-bebas attention"><i class="fa fa-angle-left"></i>{sirclo_get_text text='continue_shopping_link'}</span></a> 
            </div>

            {if !empty($cart['items'])}
                <div class="col-md-6 text-right nopadding">
                    <a class="btn-flat btn btn-lg blue-button pull-right s-large-button s-to-checkout-page" href="{$links['cart_place_order']}">{sirclo_get_text text='check_out_title'}</a>
                </div>
            {/if}
        </div>
    </div>
</div>

{/block}

{block name="footer"}
<script type="text/javascript">
    $('form.cart-shipping').find('select').change(function (ev) {
        $(this).closest('form').submit();
    });
</script>
{/block}
