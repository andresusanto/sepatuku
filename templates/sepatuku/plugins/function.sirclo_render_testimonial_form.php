<?php
 /**    Plugin File */
 /**
  * Function to generate testimonial form
  *
  * This function generate testimonial form
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the testimonial form <form>...</form>
  */
function smarty_function_sirclo_render_testimonial_form($params, $template)
{
    $html = Helper_Renderer::sircloRenderTestimonialForm($params);
    return $html;
}
