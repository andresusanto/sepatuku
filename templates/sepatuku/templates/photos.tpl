{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="container">
        <h1>{sirclo_get_text text='lookbook_title'}</h1>
        <h3>{$active_category.title}</h3>
    </div>
	
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-3">
			<div class="list-group table-of-contents">
				{if !empty($categories)}
                    {call skeleton_render_sidebar_category categories=$categories level=0}
                {/if}
			</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{if !empty($photos)}
				{foreach $photos as $photo}
					<h3>{$photo.title}</h3>
					{foreach $photo.images as $pi}
						<img src="{$pi|sirclo_file_add_suffix:'_1000'}" width="100%">
					{/foreach}
					{$photo.description}
				{/foreach}
			{/if}
		</div>
	</div>
{/block}
