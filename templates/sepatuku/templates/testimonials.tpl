{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="row s-main-content s-testimonial-page">
        <div class="col-md-12 s-breadcrumb s-bottom-padding">
            <a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
            <span>&nbsp;/&nbsp;</span>
            <a href="{$links.testimonials}">{sirclo_get_text text='testimonial_title'}</a>
        </div>
        <div class="col-md-12 s-content-title">
            <h1>{sirclo_get_text text='testimonial_title'}</h1>
            <hr/>
        </div>
        <div class="col-md-12 s-top-margin s-content">
            <i class="quote-icon fa fa-quote-left"></i>
            <div class="quote-slider">
                {if !empty($testimonials)}
                    {foreach $testimonials as $t}
                        <div class="slide">
                            {$t.content}<br/>
                            <div class="col-md-2 col-md-offset-4 s-top-margin s-bottom-margin">
                                <img class="s-fullwidth s-padding" src="{sirclo_resource file=$t.image}">
                            </div>
                            <div class="identity col-md-2 nopadding">
                                {$t.author}<br/>
                                <!-- <small class="location"></small> -->
                            </div>
                        </div>
                    {/foreach}
                {/if}
            </div>
            <div>
                <div>{sirclo_get_text text='testimonial_story_text'}</div>
                <small>{sirclo_get_text text='testimonial_share_text'}</small><br/>
                <button class="btn blue-button btn-lg s-top-margin" onclick="window.location='{$links.testimonials}/submit'">
                        {sirclo_get_text text='add_testimonial_link'}
                </button>
            </div>
        </div> 
    </div>
{/block}
