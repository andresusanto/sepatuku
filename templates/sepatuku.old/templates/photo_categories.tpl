{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="container" id="common-page-header">
        <h1>{sirclo_get_text text='lookbook_title'}</h1>
    </div>

    <div class="container" id="account-content">
        <div class="row sirclo-no-negative col-wrap" id="photo-categories">
            {if !empty($categories)}
                {call skeleton_render_photo_categories categories=$categories col_count=4}
            {/if}
            
        </div>
    </div>
{/block}
