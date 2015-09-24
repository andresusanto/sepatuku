{function skeleton_render_main_navbar nav_links=array() pos=left}
    {if $pos == 'left'}
        {$i = 0}
        {foreach $nav_links as $key=>$nav}
            {if $key != 'home'}
                {if $i lt (count($nav_links)-1)/2}
                    {$col = count($nav_links)-1 - ((count($nav_links)-1)/2)|floor}
                    {if isset($nav.sub_nav)}
                        <div class="col-md-{(int) (12/$col)}">
                            <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <div class="s-menu-text-big">{$nav.title}</div>
                            </a>
                            <ul class="dropdown-menu">
                                {foreach $nav.sub_nav as $sn}
                                    <li><a href="{$sn.link}">{$sn.title}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    {else}
                        <div class="col-md-{(int) (12/$col)}">
                            <a href="{$nav.link}">
                                <div class="s-menu-text-big">{$nav.title}</div>
                            </a>
                        </div>
                    {/if}
                {/if}
                {$i=$i+1}
            {/if}
        {/foreach}
    {else if $pos == 'right'}
        {$i = 0}
        {foreach $nav_links as $key=>$nav}
            {if $key != 'home'}
                {if $i gte (count($nav_links)-1)/2}
                    {$col = count($nav_links)-1 - ((count($nav_links)-1)/2)|ceil}
                    {if isset($nav.sub_nav)}
                        <div class="col-md-{(int) (12/$col)}">
                            <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <div class="s-menu-text-big">{$nav.title}</div>
                            </a>
                            <ul class="dropdown-menu">
                                {foreach $nav.sub_nav as $sn}
                                    <li><a href="{$sn.link}">{$sn.title}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    {else}
                        <div class="col-md-{(int) (12/$col)}">
                            <a href="{$nav.link}">
                                <div class="s-menu-text-big">{$nav.title}</div>
                            </a>
                        </div>
                    {/if}
                {/if}
                {$i=$i+1}
            {/if}
        {/foreach}
    {else if $pos == 'mobile'}
        {foreach $nav_links as $key=>$nav}
            {if $key != 'home'}
                {if isset($nav.sub_nav)}
                    <li class="drawer-menu-item dropdown drawer-dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false">{$nav.title}<span class="caret"></span></a>
                        <ul class="drawer-submenu dropdown-menu" role="menu">
                            {foreach $nav.sub_nav as $sn}
                                <li class="drawer-submenu-item"><a href="{$sn.link}">{$sn.title}</a></li>
                            {/foreach}
                        </ul>
                    </li>
                {else}
                    <li class="drawer-menu-item"><a href="{$nav.link}">{$nav.title}</a></li>
                {/if}
            {/if}
        {/foreach}
    {/if}
{/function}

{function skeleton_render_sidebar_category categories=array()}
    {if !empty($categories)}
        <ul>
        {foreach $categories as $category}
            {if !empty($category.is_active)}
                {$is_active_class = "active"}
            {else}
                {$is_active_class = ""}
            {/if}
            <li class="{$is_active_class}">
                <a href="{$category.link}">{$category.title}</a>
                {if !empty($category.is_active) and !empty($category['sub_nav'])}
                    {call skeleton_render_sidebar_category categories=$category.sub_nav}
                {/if}
            </li>
        {/foreach}
        </ul>
    {/if}
{/function}

{function skeleton_pluralize singular="" plural="" count=0}
    {if count > 1}
        {$count} {$plural}
    {else}
        {$count} {$singular}
    {/if}
{/function}

{function skeleton_render_products_product products=array() col_count=0}
    {if (count($products) == 0) }
        <p class="no-product">
            There are no products in this category.
        </p>
    {/if}

    {foreach $products as $product}
        <div class="span-sirclo4-1 products-product product">
        
        {if !empty($product.images)}
            <div class="product-image">
                <a href="{$product.link}"><img src="{$product.images.0|sirclo_file_add_suffix:'_tn'}"></a>
            </div>
        {/if}
        
        <div class="product-action">
            <div class="quick_view"><a href="{$product.link}?viewmode=quickview" class="quickview">&nbsp;</a></div>
            <div class="add_cart"><a href="{$product.link}">{sirclo_get_text text='misc_add_to_cart'}</a></div>
            <div class="clearfix"></div>
        </div>
        

        <div class="product-name">
          <a href="{$product.link}">{$product.title}</a>
        </div>

        {if !empty($product.brand)}
        <div class="product-label">
          {$product.brand}
        </div>
        {/if}

        <div class="product-price">            
          {if !empty($product.usual_price_raw)}
            <span class="usual-price">{$active_currency} {$product.usual_price_raw|number_format:2}</span>
            <span class="now-price">{$active_currency} {$product.price_raw|number_format:2}</span>
          {else}
            {$active_currency} {$product.price_raw|number_format:2}
          {/if}
        </div>

        {if ($product.is_new or !$product.is_in_stock or !empty($product.usual_price_raw) or $product.is_backorder)}
          <div class="product-special-icon">
            {if $product.is_new and !$product.is_backorder and $product.is_in_stock and empty($product.usual_price_raw)}
              <img src="{sirclo_resource file='images/icon-product-new.png'}"/>
            {/if}

            {if !empty($product.usual_price_raw) and $product.is_in_stock and !$product.is_backorder}
              <img src="{sirclo_resource file='images/icon-product-sale.png'}"/>
            {/if}

            {if !$product.is_in_stock and !$product.is_backorder}
              <img src="{sirclo_resource file='images/icon-product-oos.png'}"/>
            {/if}

            {if $product.is_backorder}
              <img src="{sirclo_resource file='images/icon-product-stock.png'}"/>
            {/if}
          </div>
        {/if}
        
        </div>

        {if ($product@key % $col_count == $col_count-1)}
            <div class="clearfix"></div>
        {/if}
    {/foreach}
{/function}

{function skeleton_render_photo_categories categories=array() col_count=0}
    {if (count($categories) == 0) }
        <p class="no-product">
            There are no categories in this category.
        </p>
    {/if}

    {foreach $categories as $category}
        <div class="span-sirclo4-1 categories-category">
        
        {if !empty($category.images)}
            <div class="category-image">
                <a href="{$category.link}"><img src="{$category.images.0}"></a>
            </div>
        {/if}

            <div class="category-name">
                <a href="{$category.link}">{$category.title}</a>
            </div>
        </div>

        {if ($category@key % $col_count == $col_count-1)}
            <div class="clearfix"></div>
        {/if}
    {/foreach}
{/function}

{function skeleton_render_filters filters=array()}
    {if !empty($filters)}
        <form action="{if !empty($paging.link)}{$paging.link}{/if}" method="get">
        {foreach $filters as $ff}
            {if !isset($ff.options) OR $ff.options}
                <div class="sidebar-header">
                    {$ff['title']}
                </div>
                <ul>
                    {if !empty($ff['options'])}
                        {if empty($ff['is_multiple'])}
                            {foreach $ff['options'] as $ffo}
                                {if !empty($ff['selected']) and !is_array($ff['selected']) and $ff['selected'] == $ffo['value']}
                                    {assign var=is_active_class value="active"}
                                {else}
                                    {assign var=is_active_class value=""}
                                {/if}
                                <li class="{$is_active_class}">
                                    {$_filter_params = $paging.params}
                                    {$_filter_params.page = NULL}
                                    {if $is_active_class}
                                        {$_filter_params["filter_`$ff.value`"] = NULL}
                                    {else}
                                        {$_filter_params["filter_`$ff.value`"] = $ffo.value}
                                    {/if}
                                    {$_filter_query = http_build_query($_filter_params)}
                                    <a href="?{$_filter_query}">
                                        {$ffo['title']}
                                        <i class="icon-chevron-right"></i>
                                    </a>
                                </li>
                            {/foreach}
                            <input type="hidden" {if !empty($ff.selected)} value="{$ff.selected}"{/if} name="filter_{$ff.value}">
                        {else}
                            {foreach $ff['options'] as $ffo}
                                {if !empty($ff['selected']) and is_array($ff['selected']) and in_array($ffo['value'], $ff['selected'])}
                                    {assign var=is_active_class value="checked"}
                                {else}
                                    {assign var=is_active_class value=""}
                                {/if}

                                
                                <li class="{$is_active_class} filter-multiple">
                                    <input name="filter_{$ff.value}[]" value="{$ffo.value}" type="checkbox" onchange="this.form.submit()" {$is_active_class}>
                                        {$ffo['title']}
                                </li>
                            {/foreach}
                        {/if}
                    {else}
                        <li class="">
                            <input type="text" name="filter_{$ff.value}"{if !empty($ff.selected)} value="{$ff.selected}"{/if} />
                        </li>
                    {/if}
                </ul>
                {if !empty($ff.operator) AND ($ff.operator == 'between')}
                    <div class="range" style="margin-bottom: 20px;">
                        {$_selected = ''}{if isset($ff.selected)}{$_selected = $ff.selected}{/if}
                        {$_exploded = explode(' - ', $_selected)}
                        {$_min_range = ''}{if isset($_exploded.0)}{$_min_range = $_exploded[0]}{/if}
                        {$_max_range = ''}{if isset($_exploded.1)}{$_max_range = $_exploded[1]}{/if}
                        <input type="text" placeholder="min" class="range-min" data-type="min" value="{$_min_range}" />
                        <span>TO</span>
                        <input type="text" placeholder="max" class="range-max" data-type="max" value="{$_max_range}" />
                        <input type="hidden" name="{$ff.value}">
                        <div class="clearfix"></div>
                    </div>
                {/if}
            {/if}
        {/foreach}
        <div class="clearfix"></div>
        <div class="filter-submit">
              <div class="filter-submit"> <button type="submit" class="btn-flat">Filter</button></div>
        </div>
        </form>
    {/if}
{/function}

{function gr_pagination_number first="first" last="last" prev="prev" next="next" view_all="View All"}
    {if !empty($paging)}
        {$_dot = ''}
        {$_limit_number = 6}
        {$_add_page = 2}
        {if $paging.total_pages > 1}
<ul>
            {assign var="_parameters" value=""}
            {if !empty($paging.params)}
                {$_parametersRaw = ''}
                {foreach $paging.params as $_param}
                    {if $_param@key !== 'page'}
                        {$_parametersRaw=$_parametersRaw|cat:'&'|cat:$_param@key|cat:'='|cat:$_param} 
                    {/if}
                {/foreach}
                {$_parameters=$_parametersRaw}
            {/if}
                {if $paging.current_page > 1}
                    <li>
                        <a href="?page={$paging.current_page-1}{$_parameters}">&lt;</a>
                    </li>
                {/if}
                {if $paging.current_page <= $_limit_number}
                    {$_max = 0}
                    {if $paging.total_pages > $_limit_number}
                        {$_dot = "<span class='paging-item'><a href=\"?page=`$_limit_number + 1``$_parameters`\">...</a></span>"}
                        {$_max = $_limit_number}
                    {else}
                        {$_max = $paging.total_pages}
                    {/if}
                    {for $i = 1 to $_max}
                        {$_active = ''}
                        {if $i == $paging.current_page}

<span class="currentNav">{$i}</span>

{else}
                        <li>
                            <a href="?page={$i}{$_parameters}" >{$i}</a>
                        </li>
                        {/if}
                    {/for}
                    {$_dot}
                {else}
                    <li>
                        <a href="?page={$paging.current_page - $_add_page - 1}{$_parameters}">...</a>
                    </li>
                    {for $i = ($paging.current_page - $_add_page) to ($paging.current_page + $_add_page)}
                        {$_active = ''}
                        {if $i == $paging.current_page}
                            {$_active = 'active'}
                        {/if}
                        {if $i <= $paging.total_pages}
                            <li class="paging-item {$_active}">
                                <a href="?page={$i}{$_parameters}">{$i}</a>
                            </li> 
                            {$_next_page = ($paging.current_page + $_add_page + 1)}
                            {$_dot = "<span class='paging-item'><a href=\"?page=`$_next_page``$_parameters`\">...</a></span>"}
                        {else}
                            {$_dot = ''}
                        {/if}
                    {/for}
                    {$_dot}
                {/if}
                {if $paging.current_page < $paging.total_pages}
                    <li>
                        <a href="?page={$paging.current_page+1}{$_parameters}">&gt;</a>
                    </li>
                {/if}
                <li><a href="{$paging.link_all}">{$view_all}</a></li>
            </ul>
        {/if}
    {/if}
{/function} 