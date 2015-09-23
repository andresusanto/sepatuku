{extends file='includes/theme.tpl'}

{block name="body"}
<div id="common-page-header" class="testimonial-header">
    <h1>{sirclo_get_text text='testimonial_title'}</h1>
</div>

<div id="testimonials">
   
        <div class="row">

            <div class="span12">
                <a class="btn btn-flat" href="{$links['testimonials']}/submit">Add new Testimonial</a>
            </div>
 {if !empty($testimonials)}
            {foreach $testimonials as $t}
                <div class="span12">
                    <hr style="margin-top:0px;" />
                    <div class="row testimonial-row">
                        <div class="span2 testimonial-image">
                            {if !empty($t['image'])}
                                <img src="{$t['image']}" />
                            {else}
                                <img src="{sirclo_resource path='/images/testimonial-img.png'}" />
                            {/if}
                        </div>
                        <div class="span8">
                            <div class="author">{$t['author']}</div>
                            <div class="content">{$t['short_content']}</div>
                            <div class="author-desc">
                                {if !empty($t.website)}
                                    <p><em>{$t.company} (http://{$t.website})</em></p>
                                {else}
                                    <p><em>{$t.company}</em></p>
                                {/if}
                            </div>
                        </div>
                        <div class="span2">
                            {$t['timestamp_created']|date_format}
                        </div>
                    </div>
                    
                </div>
                <div class="clearfix"></div>
            {/foreach}

            {if !empty($paging)}
                <div id="testimonials-paging" class="span12">
                    {sirclo_render_pagination paging=$paging first="" last="" prev="" next="" view_all=""}
                </div>
            {/if}
        {/if}
    </div>
</div>

{/block}
