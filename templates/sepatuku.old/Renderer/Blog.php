<?php
class Renderer_Blog
{
    function renderComments($comments)
    {
        $s = '';
        $s .= "<div class=\"blog-comments\">\n";
        $s .= "<a name=\"comments\"></a>\n";
        $n_com = count($comments);
        $s_com = $this->view->getMessage('comments');
        if ($n_com == 1) {
            $s_com = $this->view->getMessage('comment');
        }
        if ($n_com) {
            $s .= "<h3>$n_com $s_com</h3>\n";
        }
        foreach ($comments as $comment) {
            $s .= "<div class=\"blog-comment\">\n";
            $comment_content = htmlspecialchars($comment['content']);
            $s .= "<p>$comment_content</p>\n";
            $comment_author = htmlspecialchars($comment['author']);
            $comment_date = date('j F Y \a\t H:i', $comment['timestamp']);
            if ($comment['website']) {
                $comment_website = htmlspecialchars($comment['website']);
                $s .= "<p class=\"blog-comment-author\">by <a href=\"$comment_website\">$comment_author</a> on $comment_date</p>\n";
            } else {
                $s .= "<p class=\"blog-comment-author\">by $comment_author on $comment_date</p>\n";
            }
            $s .= "</div>\n";
        }
        $s .= "</div>\n";
        return $s;
    }

    function renderCommentForm($form)
    {
        $s = '';
        $s .= "<div class=\"blog-comment-input\">\n";
        $s .= "<h3>" . $this->view->getMessage('leave_a_comment') . "</h3>\n";
        $proc = new Form_Processor();
        $proc->form = $form;
        $s .= $proc->htmlRender();;
        $s .= "</div>\n";
        return $s;
    }

    function renderBlogPosted($article, $is_full=FALSE)
    {
        $s = '';
        $cats = $article['categories'];
        $categories = '';
        foreach ($cats as $key => $cat) {
            $cat_name = $cat['name'];
            $cat_link = $cat['link'];
            if ($key > 0) {
                $categories .= ", ";
            }
            $categories .= "<a href=\"$cat_link\">$cat_name</a>";
        }
        $author = $article['author'];
        $article_link = $article['link'];
        $date = date('j M Y \a\t H:i', $article['timestamp']);
        $s .= "<p class=\"blog-posted\">\n";
        $isoDate = date('c', $article['timestamp']);
        $authorTag = "<span itemprop=\"author\">$author</span>";
        if (isset($article['author_link']) && $article['author_link']) {
            $authorLink = $article['author_link'];
            $authorTag = "<a href=\"$authorLink\" target=\"_blank\">$authorTag</a>";
        }
        $s .= "by $authorTag on <span itemprop=\"datePublished\" datetime=\"$isoDate\">$date</span><br/>\n";
        if ($categories) {
            $s .= "Posted in: $categories<br/>\n";
        }
        $n_com = $article['comments'];
        $s_com = 'comments';
        if ($n_com == 1) {
            $s_com = 'comment';
        }
        if (!$is_full) {
            if ($n_com) {
                $s .= "<a href=\"$article_link#comments\">$n_com $s_com</a>\n";
            } else {
                $s .= "<a href=\"$article_link#comments\">leave a comment</a>\n";
            }
        }
        $s .= "</p>\n";
        return $s;
    }

    function renderArticle($article, $is_full=FALSE)
    {
        $s = '';
        $s .= "<div class=\"blog-article\">\n";
        $title = htmlspecialchars($article['title']);
        $snippet = $article['snippet'];
        $article_link = $article['link'];
        $s .= "<h2><a href=\"$article_link\">$title</a></h2>\n";
        $s .= $snippet . "\n";

        if (!$article['is_complete']) {
            $s .= "<p class=\"blog-read-more\"><a href=\"$article_link\">read more &hellip;</a></p>\n";
        }
        
        $s .= $this->renderBlogPosted($article, $is_full);
        if ($is_full) {
            $s .= $this->renderComments($article['comments']);
            $article['comment_form']['id'] = 'blog-comment-form';
            $article['comment_form']['action'] = $article['comment_post_url'];
            $article['comment_form']['submit_text'] = $this->view->getMessage('post_comment');
            $s .= $this->renderCommentForm($article['comment_form']);
        }
        $s .= "</div>\n";
        return $s;
    }

    function renderArticles($articles, $links)
    {
        $s = '';
        foreach ($articles as $article) {
            $s .= $this->renderArticle($article);
        }
        $s .= $this->renderLinks($links);
        return $s;
    }

    function renderLinks($links)
    {
        $s = '';
        $s .= "<div class=\"blog-paging\">\n";
        if ($links['prev']) {
            $prev_link = $links['prev'];
            $s .= "<div class=\"blog-paging-prev\"><a href=\"$prev_link\">&laquo; Older Entries</a></div>\n";
        }
        if ($links['next']) {
            $next_link = $links['next'];
            $s .= "<div class=\"blog-paging-next\"><a href=\"$next_link\">Newer Entries &raquo;</a></div>\n";
        }
        $s .= "</div>\n";
        return $s;
    }

    function renderPostList($articles)
    {
        $s = '';
        $s .= "<ul>\n";
        foreach ($articles as $article) {
            $title = htmlspecialchars($article['title']);
            $link = $article['link'];
            $s .= "<li><a href=\"$link\">$title</a></li>\n";
        }
        $s .= "</ul>\n";
        return $s;
    }

    function renderCategories($cats)
    {
        $s = '';
        $s .= "<ul>\n";
        foreach ($cats as $cat) {
            $name = $cat['name'];
            $link = $cat['link'];
            $n_arts = $cat['n_articles'];
            $s .= "<li><a href=\"$link\">$name</a> ($n_arts)</li>\n";
        }
        $s .= "</ul>\n";
        return $s;
    }
}
