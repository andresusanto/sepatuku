{extends file='includes/theme.tpl'}

{block name="body"}
<div id="common-page-header">
    <h1>{sirclo_get_text text='new_password_label'}</h1>
</div>

<div id="reset-password-content" class="container box-shadow">
    <div id="reset-password-form">
        {sirclo_render_reset_password_edit form_class="sirclo-form"}
    </div>
</div>

{/block}

{block name="footer"}
<script type="text/javascript">
    $('.sirclo-form').validate({
        rules : {
            confirm_new_password : {
                equalTo : "#input_new_password"
            }
        },
        messages : {
            confirm_new_password : {
                equalTo : "Please input the same password as above"
            }
        }
    });
	$(".btn-flat").addClass( "btn btn-lg reg-button s-add-to-cart" );
</script>
{/block}
