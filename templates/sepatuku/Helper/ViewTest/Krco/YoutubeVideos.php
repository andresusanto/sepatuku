<?php
class Helper_ViewTest_Krco_YoutubeVideos
{
    static function getCase_StoreYoutubeVideos_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Youtube Videos', 'youtube_videos', 'youtube_videos');
        $testNormal += array(
            'youtube_videos' => $controller->getTestYoutubeVideos(),
            'paging' => $controller->getTestPaging(),
            'categories' => $controller->getTestCategories(),
            'active_category' => $controller->getTestActiveCategory(),
        );
        return $testNormal;
    }

    static function getCase_StoreYoutubeVideoDetails_TestNormal($controller)
    {
        $testNormal = $controller->_getViewTestDefaultData('Page Youtube Video Details', 'youtube_videos', 'youtube_videos');
        $testNormal += array(
            'youtube_video' => $controller->getTestYoutubeVideo(3),
        );
        if ($controller->getObjKrcoConfig('with_prev_next')) {
            $testNormal['prev_next_youtube_videos'] = $controller->getTestYoutubeVideos(2);
        }
        return $testNormal;
    }
}
