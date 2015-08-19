{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="row s-main-content s-checkout-page">
        <div class="col-md-12 s-breadcrumb s-bottom-padding">
            <a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
            <span>&nbsp;/&nbsp;</span>
            <a href="{$links.cart_place_order}">{sirclo_get_text text='check_out_title'}</a>
        </div>
        <div class="col-md-12 s-content-title">
            <h1>{sirclo_get_text text='check_out_title'}</h1>
            <hr/>
        </div>
        <div class="col-md-5 s-top-margin">
            {sirclo_render_cart_table cart=$cart cart_table_mode='mini' with_shipping_country=true show_header=true}
            <!-- <table>
                <thead>
                    <tr>
                        <td><big class="s-bebas">{sirclo_get_text text='order_summary_title'}</big></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2 &times; Nike - Ardilla</td>
                        <td class="text-right">3.000.000</td>
                    </tr>
                    <tr>
                        <td class="text-right">Item total</td>
                        <td class="text-right">3.000.000</td>
                    </tr>
                    <tr>
                        <td class="text-right">Shipping and handling</td>
                        <td class="text-right">100.000</td>
                    </tr>
                    <tr>
                        <td class="text-right"><big class="s-bebas">TOTAL</big></td>
                        <td class="text-right"><big class="s-bebas">IDR 3.100.000</big></td>
                    </tr>
                </tbody>
            </table> -->
        </div>
        <div class="col-md-7 s-top-margin">
            {if !empty($member)}
                {sirclo_render_place_order_form form_class="s-half-form" member=$member paypal=$paypal shipping_city=$cart['shipping_city'] shipping_country=$cart['shipping_country'] link_terms=$links['terms'] link_privacy=$links['privacy'] label_bank_transfer=$static_contents['Bank Account Info'] btn_class="btn btn-lg blue-button s-checkout-submit" city_enabled=true _payment_methods=$payment_methods with_shipping_methods=true}
            {else}
                {sirclo_render_place_order_form form_class="s-half-form" paypal=$paypal shipping_city=$cart['shipping_city'] shipping_country=$cart['shipping_country'] link_terms=$links['terms'] link_privacy=$links['privacy'] label_bank_transfer=$static_contents['Bank Account Info'] btn_class="btn btn-lg blue-button s-checkout-submit" city_enabled=true _payment_methods=$payment_methods with_shipping_methods=true}
            {/if}
        </div>
    </div>
{/block}
