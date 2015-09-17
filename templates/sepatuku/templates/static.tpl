<!-- 
SEPATUKU SIRCLO THEME 
 (c) 2015 by AMBISNIS.COM
 Enquiry: sales@ambisnis.com
-->

{extends file='includes/theme.tpl'}

{block name="body"}
    <div class="row s-main-content s-static-page">
        <div class="col-md-12 s-breadcrumb s-bottom-padding">
            <a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
            <span>&nbsp;/&nbsp;</span>
            <a href="#">{sirclo_get_text text='static_title'}</a>
        </div>
        <div class="col-md-12 s-content-title">
            <h1>{sirclo_get_text text='static_title'}</h1>
            <hr/>
        </div>

        <div class="col-md-4 s-static-sidebar s-top-margin">
            {sirclo_render_static_sidebar nav=$static_data.nav nav_title=$static_data.nav_title}
        </div>

        <div class="col-md-8 s-content s-top-margin">
            {$static_data.content}
        </div>
    </div>
{/block}
