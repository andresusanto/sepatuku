<!-- 
SEPATUKU SIRCLO THEME 
 (c) 2015 by AMBISNIS.COM
 Enquiry: sales@ambisnis.com
-->

{extends file='includes/theme.tpl'}

{block name="body"}
<div class="row s-main-content">
	<div class="col-md-12">
		<img src="{sirclo_resource file='images/product-category-image.jpg'}" class="img-responsive"/>
	</div>

	{if !empty($query)}
        <div id="common-page-header">
        	<div class="col-md-12 s-breadcrumb s-bottom-padding s-top-padding">
            	{sirclo_render_breadcrumb breadcrumb=$breadcrumb}
            </div>
            <div class="col-md-12 s-content-title">
            	<h1>{sirclo_get_text text='search_result_title'} '{$query}'</h1>
            </div>
        </div>
    {elseif !empty($active_category)}
        <div id="common-page-header" {if !empty($active_category.images)}{/if}>
        	<div class="col-md-12 s-breadcrumb s-bottom-padding s-top-padding">
            	{sirclo_render_breadcrumb breadcrumb=$breadcrumb}
            </div>
            <div class="col-md-12 s-content-title">
            	<h1>{$active_category.title}</h1>
            </div>

            <div class="col-md-12">
				<div class="s-categories">
					{if !empty($categories)}
						{call skeleton_render_sidebar_category categories=$categories}
					{/if}
				</div>
			</div>
        </div>
    {/if}

	<!-- <div class="col-md-12 s-breadcrumb s-bottom-padding s-top-padding">
		{sirclo_render_breadcrumb breadcrumb=$breadcrumb}
		<a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
		<span>&nbsp;/&nbsp;</span>
		<a href="{$links.products}">{sirclo_get_text text='new_arrivals_title'}</a>
	</div>
	<div class="col-md-12 s-content-title">
		<h1>{sirclo_get_text text='search_result_title'}</h1>
		<hr/>
		<h2>{sirclo_get_text text='categories_title'}</h2>
	</div> -->


	
	<div class="col-md-12 s-products">
		<div class="row sirclo-negative">
			{call skeleton_render_products_product products=$products col_count=3}
		</div>
	</div>
	<div id="products-list-top" class="row sirclo-negative s-pagination">
		<div class="span-sirclo4-1">
			&nbsp;
		</div>
		<div id="products-list-paging" class="span-sirclo4-2">
			{if !empty($paging)}
			{sirclo_render_pagination paging=$paging first="" last="" prev="" next="" view_all="View All"}
			{/if}
		</div>
	</div> 
	
</div>


<div class="s-show-product">
        <div class="s-overlay">
        </div>
        <div class="s-quickview col-md-10 col-md-offset-1">
            <div class="s-close">&times;</div>
			<!--
            <div class="col-md-12 s-content-title">
                <h1>{$products.0.title}</h1>
                <hr/>
            </div>

            <div class="col-md-7 s-bottom-margin s-top-margin">
                {if !empty($products.0.images)}
                    {$_small_url = $products.0['images'][0]|replace:'folder':'small'|replace:'jpg':'png'}
                    {$_large_url = $products.0['images'][0]|replace:'folder':'large'}
                    
                    <img class="s-fullwidth" id="product-zoom" src="{$_small_url}" data-zoom-image="{$_large_url}"/><br/>

                    <div id="product-zoom-gallery" style='width=" 500pxfloat:left;="" "="'>
                        {foreach $products.0['images'] as $img_value}
                            {$_small_url = $img_value|replace:'folder':'small'|replace:'jpg':'png'}
                            {$_large_url = $img_value|replace:'folder':'large'}

                            <a href="#" class="elevatezoom-gallery active" data-update="" data-image="{$_small_url}" data-zoom-image="{$_large_url}">
                            <img class="" src="{$_small_url}" width="100"></a>
                        {/foreach}
                    </div>
                {/if}
            </div>

            <div class="col-md-5 s-top-margin s-product-detail">
                <h2>{$active_currency} {$products.0.price_raw|number_format:2}</h2>
                <h3>{sirclo_get_text text='description_title'}</h3>
                <div>
                    {$products.0.description}
                </div>
                <h3>SHARE</h3>
                [to be added]
                <div class="s-strike s-top-margin s-bottom-margin">
                    <span><img src="/images/wavy.png"></span></span>
                </div>

                {sirclo_render_product_add_to_cart product=$products.0 action=$links.cart}

            </div>
			-->
			<iframe id="konten-produk" src="" style="border:none;" width="100%" height="100%"></iframe>
        </div>
    </div>
{/block}
