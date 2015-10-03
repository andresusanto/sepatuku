{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="row container" id="common-page-header">
        <h1>{$static_data.title}</h1>
        {sirclo_render_breadcrumb breadcrumb=$breadcrumb}
		
		<div id="static-sidebar" class="col-md-3">
			{sirclo_render_static_sidebar nav=$static_data.nav nav_title=$static_data.nav_title}
		</div>
		<div id="products-list-list" class="col-md-9">
			{$static_data.content}
		</div>
    </div>
{/block}

{block name="footer"}
    <script type="text/javascript">
        $("#static-sidebar ul").addClass( "nav nav-pills nav-stacked s-sidebar-anak" );
    </script>
{/block}
