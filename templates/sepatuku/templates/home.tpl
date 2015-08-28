{extends file='includes/theme.tpl'}

{block name="body"}
  <div class="row s-main-content s-home-page">
    {if !empty($slides)}
      <div class="col-md-12">
        <div class="home-slider">
          {foreach $slides as $slide}
            <div class="slide">
              <img class="" lass="s-fullwidth s-padding" src="{$slide.image}">
            </div>
          {/foreach}
        </div>
      </div>
    {/if}
    <div class="s-editable-desc col-md-10 col-md-offset-1">
      {if !empty($static_contents['Short About'])}
        {$static_contents['Short About']}
      {/if}
    </div>

    <div class="col-md-12 s-top-margin">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h1 class="nopadding nomargin">{sirclo_get_text text='featured_products_title'}</h1>
        </div>
        <div class="col-md-2">
          <button id="see-all-products" class="s-title-button btn-warning btn pull-right">{sirclo_get_text text='see_all'}&nbsp;&nbsp;<small><i class="fa fa-play"></i></small></button>
        </div>
      </div>
      <div class="s-doubly-separator"></div>
    </div>
    <div class="col-md-12 s-products">
      {call skeleton_render_products_product products=$featured_products col_count=4}
    </div>

    <div class="col-md-12 s-top-margin">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h1 class="nopadding nomargin">{sirclo_get_text text='labels'}</h1>
        </div>
        <div class="col-md-2">
          <button id="see-labels" class="s-title-button btn-warning btn pull-right">{sirclo_get_text text='see_all'}&nbsp;&nbsp;<small><i class="fa fa-play"></i></small></button>
        </div>
      </div>
      <div class="s-doubly-separator"></div>
    </div>
    <div class="col-md-12 s-bottom-margin s-bottom-padding">
      {if isset($labels)}
        {foreach $labels as $label}
          <div class="col-md-7ths">
            <img src="{$label.images[0]}" class="s-fullwidth">
          </div>
        {/foreach}
      {else}
        <div style="text-align: center">{sirclo_get_text text='no_labels'}</div>
      {/if}
    </div>
  </div>

  <div class="s-show-product">
    <div class="s-overlay">
    </div>
    <div class="s-quickview col-md-10 col-md-offset-1">
      <div class="s-close">&times;</div>
      <div class="col-md-12 s-content-title">
        <h1>{$featured_products[3].title}</h1>
        <hr/>
      </div>

      <div class="col-md-7 s-bottom-margin s-top-margin">
        {if !empty($featured_products[3].detailed_images)}
          {$_small_url = $featured_products[3]['detailed_images'][0]['url']|replace:'folder':'small'|replace:'jpg':'png'}
          {$_large_url = $featured_products[3]['detailed_images'][0]['url']|replace:'folder':'large'}
          
          <img class="s-fullwidth" id="product-zoom" src="{$_small_url}" data-zoom-image="{$_large_url}"/><br/>

          <div id="product-zoom-gallery" style='width=" 500pxfloat:left;="" "="'>
            {foreach $featured_products[3]['detailed_images'] as $img_value}
              {$_small_url = $img_value['url']|replace:'folder':'small'|replace:'jpg':'png'}
              {$_large_url = $img_value['url']|replace:'folder':'large'}

              <a href="#" class="elevatezoom-gallery active" data-update="" data-image="{$_small_url}" data-zoom-image="{$_large_url}">
              <img class="" src="{$_small_url}" width="100"></a>
            {/foreach}
          </div>
        {/if}
      </div>

      <div class="col-md-5 s-top-margin s-product-detail">
        <h2>{$active_currency} {$featured_products[3].price_raw|number_format:2}</h2>
        <h3>{sirclo_get_text text='description_title'}</h3>
        <div>
          {$featured_products[3].description}
        </div>
        <h3>{sirclo_get_text text='share'}</h3>
        [to be added]
        <div class="s-strike s-top-margin s-bottom-margin">
          <span><img src="/images/wavy.png"></span></span>
        </div>

        {sirclo_render_product_add_to_cart product=$featured_products[3] action=$links.cart}

      </div>
    </div>
  </div>
{/block}
