{extends file='includes/theme.tpl'}

{block name="body"}
<div id="common-page-header" class="testimonial-header">
    <h1>{sirclo_get_text text='testimonial_title'}</h1>
</div>

<div id="testimonials" class="row s-main-content s-testimonial-page">
	<div class="col-md-12 s-top-margin s-content">
		<i class="quote-icon fa fa-quote-left"></i>
		 {if !empty($testimonials)}
		<div class="quote-slider">
			{foreach $testimonials as $t}
			<div class="slide">
				{$t['short_content']}<br/>
				<div class="col-md-2 col-md-offset-4 s-top-margin s-bottom-margin">
					{if !empty($t['image'])}
						<img class="s-fullwidth s-padding" src="{$t['image']}" />
					{else}
						<img class="s-fullwidth s-padding" src="{sirclo_resource path='/images/testimonial-img.png'}" />
					{/if}
				</div>
				<div class="identity col-md-2 nopadding">
					{$t['author']}<br/>
					<small class="location">
					{if !empty($t.website)}
						{$t.company} (http://{$t.website}) <br/>
					{else}
						{$t.company} <br/>
					{/if}
					{$t['timestamp_created']|date_format}
					</small>
				</div>
			</div>
			{/foreach}
		</div>
		{/if}
		<br/><br/>
		<div>
			<div>You had story about our shop?</div>
			<small>Share your story with other customers</small><br/>
			<a href="{$links['testimonials']}/submit" class="btn reg-button btn-lg s-top-margin">
				ADD TESTIMONIAL
			</a>
		</div>
		
		{if !empty($paging)}
			<div id="testimonials-paging" class="span12">
				{sirclo_render_pagination paging=$paging first="" last="" prev="" next="" view_all=""}
			</div>
		{/if}
	</div>
</div>

{/block}
