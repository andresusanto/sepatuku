{extends file='includes/theme.tpl'}

{block name="body"}
<div class="row">
    <div class="container text-center" id="page-not-found">
        <img style="width:175px;" src="/resources/images/404.png" /><br/><br/>
        <p>
            {sirclo_get_text text='page_not_found_title'}
        </p>
        <p>
            <a href="/">{sirclo_get_text text='back_home_link'}</a>
        </p>
    </div>
</div>
{/block}
