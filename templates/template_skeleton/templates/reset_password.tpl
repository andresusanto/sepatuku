{extends file='includes/theme.tpl'}

{block name="body"}
<div id="common-page-header">
    <h1>{sirclo_get_text text='reset_password_title'}</h1>
    <p>{sirclo_get_text text='reset_instruction_title'}</p>
</div>

<div id="reset-password-content" class="container box-shadow">
    <div id="reset-password-form">
        {sirclo_render_reset_password_form}
    </div>
</div>

{/block}

{block name="footer"}
<script type="text/javascript">
    $('.sirclo-form').validate();
</script>
{/block}
