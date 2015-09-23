

{extends file='includes/theme.tpl'}


{block name="body"}

{if empty($smarty.get.viewmode)} {* blok kondisi untuk ajax quickview  *} 
{sirclo_render_breadcrumb breadcrumb=$breadcrumb}
{/if}

<div id="product-details" class="container">
    <div class="row">
        <div id="product-details-images" class= "span6">
            {if !empty($product.images)}
                {$_full_url = $product['images'][0]|sirclo_file_add_suffix:'_full'}
                {$_zoom_url = $product['images'][0]|sirclo_file_add_suffix:'_zoom'}

                <a href="{$_zoom_url}" class = 'cloud-zoom' id='product-img-zoom' rel="position:'inside'">
                    <img class="product-img-main" src="{$_full_url}" alt='' title="" />
                </a>

                <ul>
                    {foreach $product['images'] as $img_value}
                        {$_thumb_url = $img_value|sirclo_file_add_suffix:'_tn'}
                        {$_full_url = $img_value|sirclo_file_add_suffix:'_full'}
                        {$_zoom_url = $img_value|sirclo_file_add_suffix:'_zoom'}

                        {if $img_value@key % 5 == 0}
                            {$_class = "first"}
                        {else}
                            {$_class = ""}
                        {/if}

                        <li class="{$_class}">
                            <a href="{$_zoom_url}" class="cloud-zoom-gallery" rel="useZoom: 'product-img-zoom', smallImage: '{$_full_url}'">
                                <img class="product-img-thumb" src="{$_thumb_url}"/>
                            </a>
                        </li>

                    {/foreach}
                </ul>
            {/if}
            	            
        </div>

        <div id="product-details-details" class= "span6">
        	    
                
            <h1>{$product.title}</h1>

            <div class="price">
                {if !empty($product.usual_price_raw)}
                    <span class="usual-price">
                        <del>{$active_currency} {$product.usual_price_raw|number_format:2}</del>
                    </span>
                {/if}

                <span id="product-price" class="price">
                    {$active_currency} {$product.price_raw|number_format:2}
                </span>
            </div>

            {if !empty($member.email)}
                {$_member_email = $member.email}
            {else}
                {$_member_email = ""}
            {/if}

            {sirclo_render_product_add_to_cart product=$product member_email=$_member_email action=$links.cart}

            <hr/>

            <div class="specification">
                <h3>{sirclo_get_text text='description_title'}</h3>
                <p>{$product.specification}</p>
            </div>
			<div>
             <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style ">
            <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
            <a class="addthis_button_tweet"></a>
            <a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal"></a>
            <a class="addthis_counter addthis_pill_style"></a>
            </div>
            <script type="text/javascript">var addthis_config = {ldelim}"data_track_addressbar":true{rdelim};</script>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script>
            <!-- AddThis Button END -->
                                
                                
			</div>
			
        </div>
    </div>

{if empty($smarty.get.viewmode)} {* blok kondisi untuk ajax quickview  *} 

    {if !empty($related_products)}
        <div id="product-details-related" class="row sirclo-no-negative">
            <h2>{sirclo_get_text text='recommended_title'}</h2>
            <p>{sirclo_get_text text='other_stuff_you_like'}</p>
            <hr/>
            {call skeleton_render_products_product products=$related_products col_count=4}
        </div>
    {/if}
    
{/if}    
    
</div>
{/block}

{block name="footer"}
    <script type="text/javascript">
        var _variants = {$product['variants']|sirclo_to_json};
        var _detailedVariants = {$product|sirclo_detailed_variants_to_json:$active_currency};

        var _member = false;
        var _memberEmail = '';
        {if !empty($member)}
            _member = true;
            _memberEmail = '{$member['email']}';
        {/if}

        var _linkLogin = '';
        {if !empty($links['account_login'])}
            _linkLogin = '{$links['account_login']}';
        {/if}

        var _isInStock = false;
        {if !empty($product['is_in_stock'])}
            _isInStock = true;
        {/if}

        var _option1 = '';
        {if !empty($product['general_options']['option1']['title'])}
            _option1 = '{$product['general_options']['option1']['title']}';
        {/if}

        var _option2 = '';
        {if !empty($product['general_options']['option2']['title'])}
            _option2 = '{$product['general_options']['option2']['title']}';
        {/if}

        var product_details = new SIRCLO.ProductDetails(_variants, _member, _linkLogin, _isInStock, _option1, _option2, true, _memberEmail);
        product_details.detailedVariants = _detailedVariants;
        product_details.init();
    </script>
{/block}
