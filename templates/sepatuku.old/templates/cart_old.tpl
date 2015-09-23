{extends file='includes/theme.tpl'}

{block name="body"}

<div id="common-page-header">
    <h1>Cart</h1>
    <p>{sirclo_get_text text='review_your_items_title'}</p>
</div>

<div id="cart-content" class="container box-shadow">

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

    <hr/>
	

    <div id="cart-continue">
        <div id="cart-continue-continue">
            <a class="continue" href="{$continue_url}">{sirclo_get_text text='continue_shopping_link'}</a> 

            {if !empty($cart['items'])}
                OR
                <a class="btn-flat" href="{$links['cart_place_order']}">{sirclo_get_text text='check_out_title'}</a>
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
