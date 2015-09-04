

{extends file='includes/theme.tpl'}


{block name="body"}
    <div class="row s-main-content">
        {if empty($smarty.get.viewmode)} {* blok kondisi untuk ajax quickview  *} 
            {sirclo_render_breadcrumb breadcrumb=$breadcrumb}
        {/if}
        <div class="col-md-12 s-content-title">
            <h1>{$product.title}</h1>
            <hr/>
        </div>

        <div class="col-md-7 s-bottom-margin s-top-margin">
            {if !empty($product.images)}
                {$_small_url = $product['images'][0]|replace:'folder':'small'|replace:'jpg':'png'}
                {$_large_url = $product['images'][0]|replace:'folder':'large'}
                
                <img class="s-fullwidth" id="product-zoom" src="{sirclo_resource file=$_small_url}" data-zoom-image="{sirclo_resource file=$_large_url}"/><br/>

                <div id="product-zoom-gallery" style='width=" 500pxfloat:left;="" "="'>
                    {foreach $product['images'] as $img_value}
                        {$_small_url = $img_value|replace:'folder':'small'|replace:'jpg':'png'}
                        {$_large_url = $img_value|replace:'folder':'large'}

                        <a href="#" class="elevatezoom-gallery active" data-update="" data-image="{sirclo_resource file=$_small_url}" data-zoom-image="{sirclo_resource file=$_large_url}">
                        <img class="" src="{sirclo_resource file=$_small_url}" width="100"></a>
                    {/foreach}
                </div>
            {/if}
        </div>

        <div class="col-md-5 s-top-margin s-product-detail">
            <h2>{$active_currency} {$product.price_raw|number_format:2}</h2>
            <h3>{sirclo_get_text text='description_title'}</h3>
            <div>
                {$product.description}
            </div>
            <h3>SHARE</h3>
            [to be added]
            <div class="s-strike s-top-margin s-bottom-margin">
                <span><img src="{sirclo_resource file='images/wavy.png'}"></span></span>
            </div>

            {sirclo_render_product_add_to_cart product=$product action=$links.cart}

        </div>

{if empty($smarty.get.viewmode)} {* blok kondisi untuk ajax quickview  *} 

    {if !empty($related_products)}
        <div class="col-md-12 s-top-margin">
            <h1>{sirclo_get_text text='recommended_title'}</h1>
            <div class="s-doubly-separator"></div>
        </div>
        <div class="col-md-12 s-products">
            {call skeleton_render_products_product products=$related_products col_count=4}
        </div>
    {/if}

{/if}
    </div>

    <div class="s-show-product">
        <div class="s-overlay">
        </div>
        <div class="s-quickview col-md-10 col-md-offset-1">
            <div class="s-close">&times;</div>
            <div class="col-md-12 s-content-title">
                <h1>{$product.title}</h1>
                <hr/>
            </div>

            <div class="col-md-7 s-bottom-margin s-top-margin">
                {if !empty($product.images)}
                    {$_small_url = $product['images'][0]|replace:'folder':'small'|replace:'jpg':'png'}
                    {$_large_url = $product['images'][0]|replace:'folder':'large'}
                    
                    <img class="s-fullwidth" id="product-zoom" src="{sirclo_resource file=$_small_url}" data-zoom-image="{sirclo_resource file=$_large_url}"/><br/>

                    <div id="product-zoom-gallery" style='width=" 500pxfloat:left;="" "="'>
                        {foreach $product['images'] as $img_value}
                            {$_small_url = $img_value|replace:'folder':'small'|replace:'jpg':'png'}
                            {$_large_url = $img_value|replace:'folder':'large'}

                            <a href="#" class="elevatezoom-gallery active" data-update="" data-image="{sirclo_resource file=$_small_url}" data-zoom-image="{sirclo_resource file=$_large_url}">
                            <img class="" src="{sirclo_resource file=$_small_url}" width="100"></a>
                        {/foreach}
                    </div>
                {/if}
            </div>

            <div class="col-md-5 s-top-margin s-product-detail">
                <h2>{$active_currency} {$product.price_raw|number_format:2}</h2>
                <h3>{sirclo_get_text text='description_title'}</h3>
                <div>
                    {$product.description}
                </div>
                <h3>SHARE</h3>
                [to be added]
                <div class="s-strike s-top-margin s-bottom-margin">
                    <span><img src="{sirclo_resource file='images/wavy.png'}"></span></span>
                </div>

                {sirclo_render_product_add_to_cart product=$product action=$links.cart}

            </div>
        </div>
    </div>
{/block}
