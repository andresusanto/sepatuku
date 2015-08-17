{extends file='includes/theme.tpl'}

{block name="body"}

<div class="container" id="common-page-header">
    <h1>Thank you for your order</h1>
</div>

<div class="container" id="common-page-content">
    <div class="row">
        <div class="span12">
            <div class="wrapper">
            
            {sirclo_get_text text='cart_order_placed_info'}
            </div>
        </div>
    </div>
</div>

{/block}
