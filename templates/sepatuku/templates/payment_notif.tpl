{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="container" id="common-page-header">
        <h1>{sirclo_get_text text='payment_confirmation_title'}</h1>
    </div>

    <div class="container" id="common-page-content">
        <div id="payment-notif-form" class="wrapper">
            {if !empty($total_amount_payable)}
                <p>
                    {sirclo_get_text text='total_payment_for_this_order'}: <strong>{$active_currency} {$total_amount_payable|number_format:2}</strong>
                </p>
            {/if}
            
            <p>{$payment_instruction}</p>

            {if !empty($order_id)}
                {$_order_id = $order_id}
            {else}
                {$_order_id = ''}
            {/if}

            {if !empty($order_email)}
                {$_order_email = $order_email}
            {else}
                {$_order_email = ''}
            {/if}

            {if !empty($configs)}
                {$_configs = $configs}
            {else}
                {$_configs = array()}
            {/if}

            {sirclo_render_payment_notif_form form_class="sirclo-form" order_id=$_order_id order_email=$_order_email btn_class="btn btn-info" configs=$_configs bank_accounts=$bank_accounts}
        </div>
    </div>
{/block}

{block name="footer"}
    <script type="text/javascript">
        $('.sirclo-form').validate();
        $('#input_transaction_date').datepicker();
    </script>
{/block}
