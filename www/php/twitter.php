<?php
require_once(dirname(__FILE__) . '/functions.php');

$params = array(
    'axafrance' => array(
        'user' => 'AXAFrance',
        'filename' => 'tweet_axafrance',
        'nbTweetsToGet' => 3,
        'secondsToWait' =>30,
        'title' => "AXA France",
        'consumerKey' => '3r0QPK3u7Z2ww1S1nWyBA',
        'consumerSecret' => 'ZzGZBrSbV36mrv6BK9A2M0YN71xpXqBWIamJ1mroIc',
        'accessToken' => '465697461-LIki1RdgSOmUOC39NbuEMi6ma9GJN612G0NjWBgP',
        'accessTokenSecret' => 'xNYfgVeREIZr4ahU92WSYjuWLmbARJBghkcIZzK4M',
    )
);

echo getTweets($params['axafrance']);