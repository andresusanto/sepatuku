{extends file='includes/theme.tpl'}

{block name="body"}
<div class="row s-main-content s-home-page">
	{if !empty($slides)}
	<div class="col-md-12">
		<div class="home-slider">
			{foreach $slides as $slide}
				<div class="slide">
					{if !empty($slide.link)}
						<a href="{$slide.link}">
							{if !empty($slide.title)}
								<img class="s-fullwidth s-padding" src="{$slide.image|sirclo_file_add_suffix:'_1000'}" data-thumb="{$slide.image}"/>
							{else}
								<img class="s-fullwidth s-padding" src="{$slide.image|sirclo_file_add_suffix:'_1000'}" title="{$slide.title}" />
							{/if}
						</a>
					{else}
						{if !empty($slide.title)}
							<img class="s-fullwidth s-padding" src="{$slide.image|sirclo_file_add_suffix:'_1000'}" data-thumb="{$slide.image}"/>
						{else}
							<img class="s-fullwidth s-padding" src="{$slide.image|sirclo_file_add_suffix:'_1000'}" data-thumb="{$slide.image}" title="{$slide.title}" />
						{/if}
					{/if}
				</div>
            {/foreach}
		</div>
	</div>
	{/if}
	
	{if !empty($static_contents['Short About'])}
		<div class="s-editable-desc col-md-10 col-md-offset-1">
			{$static_contents['Short About']}
		</div>
	{/if}
	
	<div class="col-md-12 s-top-margin">
		<div class="row">
			<div class="col-md-12">
				<h1 class="nopadding nomargin">FEATURED PRODUCTS</h1>
			</div>
		</div>
		<div class="s-doubly-separator"></div>
	</div>
	<div class="col-md-12 s-products">
      {call skeleton_render_products_product products=$featured_products col_count=4}
    </div>
</div>
<!--
<div id="home-banners" class="container">
  <div class="row">
    <div class="span4">
      <div class="banner-content">
        {if !empty($static_contents['Widget 1'])}
          <p>
            {$static_contents['Widget 1']}
          </p>
        {/if}
      </div>
    </div>

    <div class="span4">
      <div class="banner-content">
        {if !empty($static_contents['Widget 2'])}
          <p>
            {$static_contents['Widget 2']}
          </p>
        {/if}
      </div>
    </div>

    <div class="span4">
      <div class="banner-content">
        {if !empty($static_contents['Widget 3'])}
          <p>
            {$static_contents['Widget 3']}
          </p>
        {/if}
      </div>
    </div>

  </div>
</div>-->

<!--
<div id="first-time-visit" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Newsletter Sign Up</h3>
  </div>
  <div class="modal-body">
    {if !empty($configs.theme_mailing_list_content)}
      {$configs.theme_mailing_list_content}
    {/if}
    <form class="sirclo-form" action="{$links['newsletter']}" method="post">
      <div class="form-wrapper">
        <input class="span5" id="appendedInputButton" type="email" name="email" placeholder="Your email..." required>
        <input style="" class="btn btn-flat" type="submit">
      </div>
    </form>
  </div>
</div>-->


<div class="s-first-time">
	<div class="s-overlay">
	</div>
	<div class="s-newletterfirst">
		<h3>Newsletter Sign Up</h3>
		<div class="s-close">&times;</div>
		{if !empty($configs.theme_mailing_list_content)}
		  {$configs.theme_mailing_list_content}
		{/if}
		<form action="{$links['newsletter']}" method="post">
			<div class="input-group">
				<input class="form-control" type="text" id="appendedInputButton" type="email" name="email" placeholder="{sirclo_get_text text='your_email_placeholder'}...">
				<span class="input-group-btn">
					<button class="btn btn-primary" type="submit">{sirclo_get_text text='subscribe'}</button>
				</span>
			</div>
		</form>
	</div>
</div>

<div class="s-show-product">
	<div class="s-overlay">
	</div>
	<div class="s-quickview col-md-10 col-md-offset-1">
		<div class="s-close">&times;</div>
		<iframe id="konten-produk" src="" style="border:none;" width="100%" height="100%"></iframe>
	</div>
</div>
{/block}

{block name="footer"}

{if !empty($is_first_visit) and !empty($configs.theme_mailing_list_popup)}
  <script type="text/javascript">
		$('.s-first-time').show();
  </script>
{/if}

{/block}
