<?php
require_once(dirname(__FILE__) . '/functions.php');

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

$params = array(
    'axapeopleprotectors' => array(
        'secondsToWait' => 5,
        'nbPostsToGet' => 2,
        'title'=>'AXA People Protectors',
        'filename' => 'fb_axapeopleprotectors',
        'profileId' => '1401788290040956',
        'thumb' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c1.0.179.179/s160x160/303451_354914147890820_247657526_a.jpg',
        'accessToken' => 'CAAGB6KXQepYBAJLCbTBlVOWJknRef75JZCuF1ABZCOdyQBeKwZCKV76OS3ZC9CdvH9GDC9JG3oC6ZBfbGRZAZBH1TuQOm9rD868ZARoZADBZBUOs0vXkkFztxJnMu4i3aC8ixTKhtqD1V7K3O2tT2525ycPDgHqRUK5ZCoOX2fqs1yQuiUTZA9jQ8y2l',
    ),
    'axavotreservice' => array(
        'secondsToWait' => 5,
        'nbPostsToGet' => 1,
        'thumb' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash4/203534_170226609767630_815146360_q.jpg',
        'title'=>'AXA Votre Service',
        'filename' => 'fb_axavotreservice',
        'profileId' => '170226609767630',
        'accessToken' => 'CAAGB6KXQepYBAJLCbTBlVOWJknRef75JZCuF1ABZCOdyQBeKwZCKV76OS3ZC9CdvH9GDC9JG3oC6ZBfbGRZAZBH1TuQOm9rD868ZARoZADBZBUOs0vXkkFztxJnMu4i3aC8ixTKhtqD1V7K3O2tT2525ycPDgHqRUK5ZCoOX2fqs1yQuiUTZA9jQ8y2l',
    )
);

if (isset($_REQUEST['page']) && isset($params[$_REQUEST['page']]) && is_array($params[$_REQUEST['page']])) {
    echo getFbPosts($params[$_REQUEST['page']]);
}