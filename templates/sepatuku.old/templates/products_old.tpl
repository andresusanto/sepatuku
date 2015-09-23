{extends file='includes/theme.tpl'}

{block name="body"}
    {if !empty($query)}
        <div id="common-page-header">
            <h1>{sirclo_get_text text='search_result_title'} '{$query}'</h1>
            {sirclo_render_breadcrumb breadcrumb=$breadcrumb}
        </div>
    {elseif !empty($active_category)}
        <div id="common-page-header" {if !empty($active_category.images)}style="background:url('{$active_category.images.0}') no-repeat 0 0 #fff;"{/if}>
            <h1>{$active_category.title}</h1>
            {sirclo_render_breadcrumb breadcrumb=$breadcrumb}
        </div>
    {/if}

    <div id="products-list" class="container box-shadow">
        <div class="row sirclo-no-negative col-wrap">
            <div id="products-list-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar">
                <div class="sidebar-header">
                    {sirclo_get_text text='categories_title'}
                </div>
                {if !empty($categories)}
                    {call skeleton_render_sidebar_category categories=$categories}
                {/if}

                {if !empty($filter_fields)}
                {call skeleton_render_filters filters=$filter_fields}
                {/if}
            </div>

            <div id="products-list-list" class="span-sirclo4-3 col">
                <div id="products-list-top" class="row sirclo-negative">
                    <div id="products-list-sort" class="span-sirclo4-1">
                        {if !empty($sorts)}
                            <form class="form-inline visible-desktop" action="" method="get">
                                Sort by
                                <select name="sort" onchange="this.form.submit();">
                                      <option value="select" > --- Select --- </option>
                                      {foreach $sorts as $sort}
                                          {if !empty($sort.is_active)}
                                              {$is_active_class = "class='active'"}
                                          {else}
                                              {$is_active_class = ""}
                                          {/if}
                                          {$_filter_params.sort = {$sort.value}}
                                          <option value="{$sort.value}" {$is_active_class}>{$sort.title}</option>
                                      {/foreach}
                                      <input type="hidden" name="query" value="{if !empty($query)}{/if}">
                                </select>
                            </form>
                        {/if}
                    </div>
                    <div id="products-list-paging" class="span-sirclo4-2">
                        {if !empty($paging)}
                        {sirclo_render_pagination paging=$paging first="" last="" prev="" next="" view_all="View All"}
                        {/if}
                    </div>
                </div>

                <div class="row sirclo-negative">
                    {call skeleton_render_products_product products=$products col_count=3}
                </div>
          
                <div id="products-list-top" class="row sirclo-negative">
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

        </div>
    </div>

{/block}
{block name="footer"}
    <script type="text/javascript">
        var filter_range_change = function(tb) {
            var min_val = $(this).parent().children('input[data-type="min"]').val();
            var max_val = $(this).parent().children('input[data-type="max"]').val();
            $(this).siblings('input[type="hidden"]').val(min_val + " - " + max_val);
        }
        $('#products-list-sidebar .range input[type="text"]').change(filter_range_change);
        $('#products-list-sidebar .range input[type="text"]').trigger('change');
    </script>
{/block}