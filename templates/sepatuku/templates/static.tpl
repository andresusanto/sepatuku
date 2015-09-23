{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="container" id="common-page-header">
        <h1>{$static_data.title}</h1>
        {sirclo_render_breadcrumb breadcrumb=$breadcrumb}
    </div>

    <div class="container" id="account-content">
        <div class="row sirclo-no-negative col-wrap">
            <div id="static-sidebar" class="span-sirclo4-1 margin-to-padding col common-sidebar">
                {sirclo_render_static_sidebar nav=$static_data.nav nav_title=$static_data.nav_title}
            </div>

            <div id="static-content" class="span-sirclo4-3 col">
                <div class="wrapper">
                    {$static_data.content}
                </div>
            </div>
        </div>
    </div>
{/block}
