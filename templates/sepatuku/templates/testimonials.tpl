{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="row s-main-content s-testimonial-page">
        <div class="col-md-12 s-breadcrumb s-bottom-padding">
            <a href="{$links.home}">Home</a>
            <span>&nbsp;/&nbsp;</span>
            <a href="{$links.testimonials}">Testimonial</a>
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
                                <img class="s-fullwidth s-padding" src="{$t.image}">
                            </div>
                            <div class="identity col-md-2 nopadding">
                                {$t.author}<br/>
                                <small class="location">{$t.location}</small>
                            </div>
                        </div>
                    {/foreach}
                {/if}
            </div>
            <div>
                <div>You had story about our shop?</div>
                <small>Share your story with other customers</small><br/>
                <button class="btn blue-button btn-lg s-top-margin">
                    ADD TESTIMONIAL
                </button>
            </div>
        </div> 
    </div>
{/block}
