{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="container" id="common-page-header">
        <h1>{sirclo_get_text text='lookbook_title'}</h1>
        <p>{$active_category.title}</p>
    </div>

    <div class="container" id="account-content">
        <div class="row sirclo-no-negative col-wrap">
            <div id="static-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar">
                {if !empty($categories)}
                    {call skeleton_render_sidebar_category categories=$categories}
                {/if}
            </div>

            <div id="photos" class="span-sirclo4-3 col">
                <div class="wrapper">
                    {if !empty($photos)}
                        {foreach $photos as $photo}
                            {foreach $photo.images as $pi}
                                <img src="{$pi|sirclo_file_add_suffix:'_1000'}">
                            {/foreach}
                            <div class="photo-title">{$photo.title}</div>
                            <div class="photo-description">
                                {$photo.description}
                            </div>
                        {/foreach}
                    {/if}
                </div>
            </div>
        </div>
    </div>
{/block}
