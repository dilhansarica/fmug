<?php
function fetchUrl($url){
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_TIMEOUT, 20);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
 
     $retData = curl_exec($ch);
     curl_close($ch); 
 
     return $retData;
}

function getFbPosts($params) {
    $filename = dirname(__FILE__) . '/../json/' . $params['filename'] . '.json';
    $fileLastUpdate = filemtime($filename);
    $jsonLastPosts = file_get_contents($filename);

    if ((time() - $fileLastUpdate) > $params['secondsToWait']) {

        $fbGraphUrl = 'https://graph.facebook.com';

        $data = fetchUrl("{$fbGraphUrl}/{$params['profileId']}/posts?access_token={$params['accessToken']}&limit=50&summary=1");
        $posts = json_decode($data, true);

        $dataLastPosts = json_decode($jsonLastPosts, true);
        $existingPostsId = array();
        
        if (isset($dataLastPosts['posts'])) {
            foreach ($dataLastPosts['posts'] as $dataLastPost) {
                $existingPostsId[] = $dataLastPost['id'];
            }
        }
        
        $aNewPosts = array();
        $indexPosts = 0;
        $newPostsId = array();
        
        while ($indexPosts < count($posts['data']) && count($aNewPosts) < $params['nbPostsToGet']) {
            $post = $posts['data'][$indexPosts++];
            
            if ($post['type'] != 'status' && (!isset($params['lang']) || (!empty($post['privacy']['description']) && stripos($post['privacy']['description'], $params['lang']) !== false))) {
                $newPostsId[] = $post['id'];
                
                $fql = urlencode("SELECT like_info, comment_info FROM stream WHERE post_id = '{$post['id']}'");
                $fqlResult = fetchUrl("{$fbGraphUrl}/fql?q={$fql}&access_token={$params['accessToken']}");
                $dataStats = json_decode($fqlResult, true);

                $newPost = array(
                    'id' => $post['id'],
                    'message' => $post['message'],
                    'title' => $params['title'],
                    'picture' => $post['picture'],
                    'thumbprofile' => $params['thumb'],
                    'link' => (isset($params['lang'])) ? 'https://www.facebook.com/' . $params['profileId'] : $post['link'],
                    'likesCount' => $dataStats['data'][0]['like_info']['like_count'],
                    'commentsCount' => $dataStats['data'][0]['comment_info']['comment_count']
                );
                $aNewPosts[] = $newPost;
            }
        }
        
        $diff = array_diff($newPostsId, $existingPostsId);
        
        if(!empty($newPostsId) && !empty($diff)) {
            $jsonLastPosts = json_encode(array('posts' => $aNewPosts));
            file_put_contents($filename, $jsonLastPosts);
        }
    }
    return $jsonLastPosts;
}

function getTweets($params) {
    $filename = dirname(__FILE__) . '/../json/' . $params['filename'] . '.json';
    $fileLastUpdate = filemtime($filename);
    $jsonLastTweets = file_get_contents($filename);

    if ((time() - $fileLastUpdate) > $params['secondsToWait']) {

        require_once(dirname(__FILE__) . '/twitteroauth/twitteroauth.php');
        
        $connection = new TwitterOAuth($params['consumerKey'], $params['consumerSecret'], $params['accessToken'], $params['accessTokenSecret']);
        $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name={$params['user']}&count=25&include_rts=false");

        $dataLastTweets = json_decode($jsonLastTweets, true);
        $existingTweetsId = array();
        
        if (isset($dataLastTweets['tweets'])) {
            foreach ($dataLastTweets['tweets'] as $dataLastTweet) {
                $existingTweetsId[] = $dataLastTweet['id'];
            }
        }
        
        $aNewTweets = array();
        $indexTweets = 0;
        $newTweetsId = array();

        while ($indexTweets < count($tweets) && count($aNewTweets) < $params['nbTweetsToGet']) {
            $tweet = $tweets[$indexTweets++];
            
            if (!isset($params['lang']) || (!empty($tweet->lang) && $tweet->lang == $params['lang'])) {
                $newTweetsId[] = $tweet->id_str;
                $newTweet = array(
                    'id' => $tweet->id_str,
                    'text' => $tweet->text,
                    'title' => $params['title'],
                    'link' => 'https://twitter.com/' . $params['user'] . '/status/' . $tweet->id_str
                );
                $aNewTweets[] = $newTweet;
            }
        }
        
        $diff = array_diff($newTweetsId, $existingTweetsId);
        
        if(!empty($newTweetsId) && !empty($diff)) {
            $jsonLastTweets = json_encode(array('tweets' => $aNewTweets));
            file_put_contents($filename, $jsonLastTweets);
        }
    }

    return $jsonLastTweets;
}