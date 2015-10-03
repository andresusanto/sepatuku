{extends file='includes/theme.tpl'}

{block name="body"}
    {if !empty($query)}
        <div id="common-page-headerz">
            <h1>{sirclo_get_text text='search_result_title'} '{$query}'</h1>
        </div>
    {elseif !empty($active_category)}
        <div id="common-page-headerz" {if !empty($active_category.images)}style="background:url('{$active_category.images.0}') no-repeat 0 0 #fff; background-size: 100% 100%;"{/if}>
            <h1>{$active_category.title}</h1>
        </div>
    {/if}

	{sirclo_render_breadcrumb breadcrumb=$breadcrumb}
	
    <div id="products-list" class="row">
		<div class="col-md-3">
			<div id="products-list-sidebar">
				<h3>{sirclo_get_text text='categories_title'} </h3>
				{if !empty($categories)}
					{call skeleton_render_sidebar_category categories=$categories}
				{/if}

				{if !empty($filter_fields)}
				{call skeleton_render_filters filters=$filter_fields}
				{/if}
			</div>
		</div>
		<div id="products-list-list" class="col-md-9">
			<div id="products-list-top" class="row sirclo-negative">
				<div id="products-list-sort" class="span-sirclo4-1">
					{if !empty($sorts)}
						<form class="form-inline visible-desktop" action="" method="get">
							Sort by
							<select name="sort" onchange="this.form.submit();" class="btn btn-default">
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
			<br/><br/>	
			<div class="row s-products">
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
        var filter_range_change = function(tb) {
            var min_val = $(this).parent().children('input[data-type="min"]').val();
            var max_val = $(this).parent().children('input[data-type="max"]').val();
            $(this).siblings('input[type="hidden"]').val(min_val + " - " + max_val);
        }
        $('#products-list-sidebar .range input[type="text"]').change(filter_range_change);
        $('#products-list-sidebar .range input[type="text"]').trigger('change');
    </script>
{/block}