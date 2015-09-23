{extends file='includes/theme.tpl'}

{block name="body"}
    
    <div id="common-page-header">
        <h1>{sirclo_get_text text='contact_us_title'}</h1>
        <img class="hidden-phone" src="{sirclo_resource path='/images/header-contact.png'}"/>
    </div>

    <div id="common-page-content">
        <div class="row">
            <div class="span6">
                <div class="wrapper">
                    {sirclo_render_contact_form form_class="sirclo-form" action=$links.contact}
                </div>
            </div>

            <div class="span6">
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
    </script>
{/block}
