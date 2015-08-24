<?php
class Helper_ViewTest_Krco_Blog
{
    static function getCase_BlogArticles_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('My Blog', 'blog', 'blog');
        $testNormal += array(
            'categories' => $controller->getNormalCategories(),
            'archives' => $controller->getNormalArchives(),
            'recent_posts' => $controller->getNormalRecentPosts(),
            'rss_url' => 'http://rss_url',
            'articles' => array(
                array(
                    'title' => 'Tom & <strong>Jerry</strong> This is a Very Long Title And Very Very Long Title',
                    'link' => 'http://www.blog.com/article',
                    'comments' => 3,
                    'snippet' => $controller->getNormalSnippet(),
                    'short_description' => Helper_ViewTest::getLoremIpsumP(1) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
                    'timestamp' => 1,
                    'author' => 'Alice',
                    'author_link' => 'http://alice',
                    'categories' => $controller->getNormalCategories(),
                    'is_complete' => FALSE,
                    'image' => $controller->resource_url . '/images/test/article1.jpg',
                    'images' => array(
                        $controller->resource_url . '/images/test/article1.jpg',
                        $controller->resource_url . '/images/test/article1b.jpg',
                    ),
                ),
                array(
                    'title' => 'Tom & <em>Jerry</em>',
                    'link' => 'http://www.blog.com/article2',
                    'comments' => 0,
                    'snippet' => $controller->getNormalSnippet(),
                    'short_description' => Helper_ViewTest::getLoremIpsumP(1) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
                    'timestamp' => time(),
                    'author' => 'Bob',
                    'author_link' => '',
                    'categories' => array(),
                    'is_complete' => TRUE,
                    'image' => '',
                    'images' => array(),
                ),
                array(
                    'title' => 'Another Article',
                    'link' => 'http://www.blog.com/article3',
                    'comments' => 3,
                    'snippet' => $controller->getNormalSnippet(),
                    'short_description' => Helper_ViewTest::getLoremIpsumP(1) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
                    'timestamp' => 1,
                    'author' => NULL,
                    'author_link' => 'http://alice',
                    'categories' => $controller->getNormalCategories(),
                    'is_complete' => FALSE,
                    'image' => $controller->resource_url . '/images/test/article1.jpg',
                    'images' => array(
                        $controller->resource_url . '/images/test/article1.jpg',
                    ),
                ),
            ),
            'articles_links' => array(
                'prev' => 'http://prev',
                'next' => '',
            ),
        );
        return $testNormal;
    }

    static function getCase_BlogArticles_TestWithPagingComplete($controller)
    {
        $testWithPagingComplete = self::getCase_BlogArticles_TestNormal($controller);
        $testWithPagingComplete['articles_links'] = array(
            'prev' => 'http://prev',
            'next' => 'http://next',
        );
        $testWithPagingComplete['categories'] = array();
        return $testWithPagingComplete;
    }

    static function getCase_BlogArticles_TestWithTitle($controller)
    {
        $testWithTitle = self::getCase_BlogArticles_TestNormal($controller);
        $testWithTitle['articles_title'] = 'Archive for the \'Travel\' Category';
        return $testWithTitle;
    }

    static function getCase_BlogArticle_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('My Blog', 'blog', 'blog');
        $staticData = $controller->getTestStaticData();
        $testNormal += array(
            'categories' => $controller->getNormalCategories(),
            'archives' => $controller->getNormalArchives(),
            'recent_posts' => $controller->getNormalRecentPosts(),
            'rss_url' => 'http://rss',
            'article' => array(
                'title' => 'Tom & <strong>Jerry</strong>',
                'link' => 'http://www.blog.com/article',
                'comments' => $controller->getNormalComments(),
                'comment_post_url' => 'http://commentposturl',
                'snippet' => $staticData['content'],
                'short_description' => Helper_ViewTest::getLoremIpsumP(1) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
                'timestamp' => 1,
                'author' => 'Alice',
                'categories' => $controller->getNormalCategories(),
                'is_complete' => FALSE,
                'comment_form' => $controller->getCommentForm(),
                'image' => $controller->resource_url . '/images/test/article1.jpg',
                'images' => array(
                    $controller->resource_url . '/images/test/article1.jpg',
                    $controller->resource_url . '/images/test/article1b.jpg',
                ),
            ),
            'related_posts' => $controller->getNormalRelatedPosts(),
            'prev_next_posts' => $controller->getNormalPrevNextPosts(),
        );
        return $testNormal;
    }

    static function getCase_BlogArticle_TestWithMessage($controller)
    {
        $testWithMessage = self::getCase_BlogArticle_TestNormal($controller);
        $testWithMessage += array(
            'message' => 'This is a success message',
            'message_type' => 'success',
        );
        $testWithMessage['article']['categories'] = array();
        $testWithMessage['article']['snippet'] = "<p>This is a very short article.</p>";
        $testWithMessage['prev_next_posts'][0] = NULL;
        return $testWithMessage;
    }

    static function getCase_BlogArticle_TestWithMessageError($controller)
    {
        $testWithMessageError = self::getCase_BlogArticle_TestNormal($controller);
        $testWithMessageError += array(
            'message' => 'This is an error message',
            'message_type' => 'error',
        );
        $testWithMessageError['article']['snippet'] = "<p>This is a very short article.</p>";
        $testWithMessageError['article']['image'] = '';
        $testWithMessageError['article']['images'] = array();
        $testWithMessageError['prev_next_posts'][1] = NULL;
        return $testWithMessageError;
    }
}
