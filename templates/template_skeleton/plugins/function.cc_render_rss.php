<?php
function smarty_function_cc_render_rss($params, $template)
{
    $html = '';
    if (isset($params['channel']) && isset($params['articles'])) {
        $atom = new Formatter_Rss();
        $html = $atom->formatArticles($params['channel'], $params['articles']);
    }
    return $html;
}
