

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

	<div class="col-md-6 s-bottom-margin s-top-margin">
		{if !empty($product.images)}
			{$_full_url = $product['images'][0]|sirclo_file_add_suffix:'_full'}
			{$_zoom_url = $product['images'][0]|sirclo_file_add_suffix:'_zoom'}

			<img class="s-fullwidth" id="product-zoom" src="{$_full_url}" data-zoom-image="{$_zoom_url}"/><br/>
		
			<div id="product-zoom-gallery" style='width=" 500pxfloat:left;="" "="'>
				{foreach $product['images'] as $img_value}
					{$_thumb_url = $img_value|sirclo_file_add_suffix:'_tn'}
					{$_full_url = $img_value|sirclo_file_add_suffix:'_full'}
					{$_zoom_url = $img_value|sirclo_file_add_suffix:'_zoom'}
					
					<a href="#" class="elevatezoom-gallery active" data-update="" data-image="{$_full_url}" data-zoom-image="{$_zoom_url}">
					<img class="" src="{$_thumb_url}" width="100"></a>


				{/foreach}
			</div>
		{/if}
	</div>
	
	<div class="col-md-6 s-top-margin s-product-detail">
		<strong><span id="product-price">{$active_currency} {$product.price_raw|number_format:2}</span></strong>&nbsp;&nbsp;
		{if !empty($product.usual_price_raw)}
			<del>{$active_currency} {$product.usual_price_raw|number_format:2}</del>
		{/if}
		<br/><br/>
		{if !empty($member.email)}
			{$_member_email = $member.email}
		{else}
			{$_member_email = ""}
		{/if}

		{sirclo_render_product_add_to_cart product=$product member_email=$_member_email action=$links.cart}

		<div class="s-strike s-top-margin s-bottom-margin">
			<span><img src="{sirclo_resource file='images/wavy.png'}"/></span>
		</div>
		
		<h3>{sirclo_get_text text='description_title'}</h3>
		<p>{$product.specification}</p>
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
	

	{if empty($smarty.get.viewmode)} {* blok kondisi untuk ajax quickview  *} 

		<div class="col-md-12">
			{if !empty($related_products)}
				<div id="product-details-related" class="s-products">
					<h2>{sirclo_get_text text='recommended_title'}</h2>
					<p>{sirclo_get_text text='other_stuff_you_like'}</p>
					<hr/>
					{call skeleton_render_products_product products=$related_products col_count=4}
				</div>
			{/if}
		</div>
		

	{/if}    
		
</div>

<div class="s-show-product">
	<div class="s-overlay">
	</div>
	<div class="s-quickview col-md-10 col-md-offset-1">
		<div class="s-close">&times;</div>
		<iframe id="konten-produk" src="" style="border:none;" width="100%" height="100%"></iframe>
	</div>
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
		
		$("#product-add-to-cart").addClass( "btn btn-lg blue-button s-fullwidth s-add-to-cart" );
		$("#product-option-option1").addClass( "btn btn-default s-fifty-dropbox" );
		$("#product-option-option2").addClass( "btn btn-default s-fifty-dropbox" );
    </script>
{/block}
