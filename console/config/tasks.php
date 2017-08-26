<?php
/**
 *
 'catchVideo'=>[
    'pattern'   =>  '35-40 * * * *',
    'route'     =>  'test/index'
 ],
'catchVideos'=>[
    'pattern'   =>  '37,38,39 * * * *',
    'route'     =>  'test/index'
],
'catch'=>[
    'pattern'   =>  '* * * * *',
    'route'     =>  'test/index'
],
 */
return [
    'testMinute'    =>  [
        'pattern' => '* * * * *',
        'route'   => 'test/index'
    ],
    'testSecond'    =>  [
        'pattern'   =>  '* * * * *',
        'route'     =>  'test/second'
    ]
];