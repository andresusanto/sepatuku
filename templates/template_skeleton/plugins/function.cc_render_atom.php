<?php
function smarty_function_cc_render_atom($params, $template)
{
    $html = '';
    if (isset($params['channel']) && isset($params['articles'])) {
        $atom = new Formatter_Atom();
        $html = $atom->formatArticles($params['channel'], $params['articles']);
    }
    return $html;
}
