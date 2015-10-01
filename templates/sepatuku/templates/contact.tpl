{extends file='includes/theme.tpl'}

{block name="body"}

<div class="s-breadcrumb s-bottom-padding">
    <a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
    <span>&nbsp;/&nbsp;</span>
    <a href="{$links.contact}">{sirclo_get_text text='contact'}</a>
</div>

<div id="common-page-header" class="col-md-12 s-content-title">
    <h1>{sirclo_get_text text='contact'}</h1>
    <hr/>
</div>

    <div id="common-page-content">
        <div class="row">
            <div class="col-md-7">
                <div class="wrapper s-half-form s-contact-form">
                    {sirclo_render_contact_form form_class="sirclo-form" action=$links.contact}
                </div>
            </div>

            <div class="col-md-5">
                <div class="wrapper">
                    {if !empty($static_contents['Contact Info'])}
                        {$static_contents['Contact Info']}
                    {/if}
                </div>
            </div>
        </div>
    </div>

{/block}

{block name="footer"}
    <script type="text/javascript">
        $('.sirclo-form').validate();
        $('.s-contact-form input[type=submit]').addClass("blue-button").addClass("btn-lg");
        $('.alert-error').addClass("alert-danger");
    </script>
{/block}
