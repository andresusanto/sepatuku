{extends file='includes/theme.tpl'}

{block name="body"}
<div class="row s-main-content s-static-page">
    <div class="col-md-12 s-breadcrumb s-bottom-padding">
        <a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
        <span>&nbsp;/&nbsp;</span>
        <a href="{$links.static}">{sirclo_get_text text='static_title'}</a>
    </div>
    <div class="col-md-12 s-content-title">
        <h1>{sirclo_get_text text='static_title'}</h1>
        <hr/>
    </div>
    
    <div class="col-md-4 s-static-sidebar s-top-margin">
        <ul>
            <li class="active">
                <big><a href="javascript:void(0);">About Us</a></big>
                <ul>
                    <li><a href="javascript:void(0);">Overview</a></li>
                    <li><a href="javascript:void(0);">History</a></li>
                    <li><a href="javascript:void(0);">Our Team</a></li>
                </ul>
            </li>
            <li>
                <big><a href="javascript:void(0);">Terms & Conditions</a></big>
                <ul>
                    <li><a href="javascript:void(0);">Sub 1</a></li>
                    <li><a href="javascript:void(0);">Sub 2</a></li>
                </ul>
            </li>
            <li>
                <big><a href="javascript:void(0);">Privacy Policy</a></big>
            </li>
            <li>
                <big><a href="javascript:void(0);">Sitemap</a></big>
                <ul>
                    <li><a href="javascript:void(0);">Sub 1</a></li>
                    <li><a href="javascript:void(0);">Sub 2</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="col-md-8 s-content s-top-margin">
        <img class="pull-right" src="img/caslon-specimen1.jpg" style="width:200px; margin-top:0px"/>
        <p><i>Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet,   </i></p>
        <h1>LEVEL 01 HEADING</h1>
        <h2>LEVEL 02 HEADING</h2>
        <h3>LEVEL 03 HEADING</h3>
        <h4>LEVEL 04 HEADING</h4>
        <h5>LEVEL 05 HEADING</h5>
        <p>Teks biasa, <a href="">link silakan dihover</a><br/><br/></p>
        <h2>TEXT STYLE</h2>
        <p>Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet,  </p><br/>
        <h2>TABLE</h2>
        <table class="s-table">
            <thead>
                <tr>
                    <td>COLUMN HEADER</td>
                    <td>COLUMN HEADER</td>
                    <td>COLUMN HEADER</td>
                    <td>COLUMN HEADER</td>
                    <td>COLUMN HEADER</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Row Header</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                </tr>
                <tr>
                    <td>Row Header</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                </tr>
                <tr>
                    <td>Row Header</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                </tr>
                <tr>
                    <td>Row Header</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                    <td>Cell Data</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>














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
