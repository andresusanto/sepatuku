{extends file='includes/theme.tpl'}

{block name="body"}
<div id="common-page-header" class="testimonial-header">
    <h1>{sirclo_get_text text='add_testimonial_link'}</h1>
</div>

<div id="testimonial">
    <div id="testimonial-submit" class="row">
        <div class="span12">
            <div class="wrapper">
            {sirclo_render_testimonial_form btn_class="btn-flat" lang="en"}
            </div>
        </div>
    </div>
</div>
{/block}

{block name="footer"}
<script type="text/javascript">
    $('.sirclo-form').validate();
</script>
{/block}
