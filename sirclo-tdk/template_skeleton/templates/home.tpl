{extends file='includes/theme.tpl'}

{block name="body"}
<div id="home-hero" class="container box-shadow">
    {if !empty($static_contents['Short About'])}
      {$static_contents['Short About']}
    {/if}
    {if !empty($slides)}
    <div class="slider-wrapper theme-light">
        <div id="slider" class="nivoSlider">
            {foreach $slides as $slide}
                {if !empty($slide.link)}
                    <a href="{$slide.link}">
                        {if !empty($slide.title)}
                            <img src="{$slide.image|sirclo_file_add_suffix:'_1000'}" data-thumb="{$slide.image}"/>

                        {else}
                            <img src="{$slide.image|sirclo_file_add_suffix:'_1000'}" title="{$slide.title}" />
                        {/if}
                    </a>
                {else}
                    {if !empty($slide.title)}
                        <img src="{$slide.image|sirclo_file_add_suffix:'_1000'}" data-thumb="{$slide.image}"/>
                    {else}
                        <img src="{$slide.image|sirclo_file_add_suffix:'_1000'}" data-thumb="{$slide.image}" title="{$slide.title}" />
                    {/if}
                {/if}
            {/foreach}
        </div>
    </div>
    {/if}
</div>

<div id="home-featured-products">
  <div class="container box-shadow">
    <h2>Featured Products</h2>     
    <div class="row sirclo-no-negative">
        {call skeleton_render_products_product products=$featured_products col_count=4}
    </div>
  </div>
</div>

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
</div>

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
</div>
{/block}

{block name="footer"}
<script type="text/javascript">
jQuery(function() {
    jQuery('#slider').nivoSlider({
	animSpeed: 500,
	pauseTime: 4000 
	});
});
</script>

{if !empty($is_first_visit) and !empty($configs.theme_mailing_list_popup)}
  <script type="text/javascript">
    $('#first-time-visit').modal();
    $('#first-time-visit form').each(function(index) {
        $(this).validate({
            errorPlacement: function(error, element) {
                if (element.attr("name") == "email") {
                    error.insertAfter(element.parent());
                }
                else {
                    error.insertAfter(element);
                }
            }
        });
    });
  </script>
{/if}

{/block}
