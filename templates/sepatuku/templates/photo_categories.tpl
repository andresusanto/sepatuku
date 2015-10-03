{extends file='includes/theme.tpl'}

{block name="body"}
    <h1>{sirclo_get_text text='lookbook_title'}</h1>
    <br/>
	<div class="row">
		{if !empty($categories)}
			{call skeleton_render_photo_categories categories=$categories col_count=4}
        {/if}
	</div>
{/block}
