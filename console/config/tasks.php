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

    /**
     * 定时任务服务器
     */
    \console\models\popo\Task::SERVER_REGULAR=>[
        'testMinute'    =>  [
            'pattern' => '* * * * *',
            'route'   => 'test/index'
        ],
        'testSecond'    =>  [
            'pattern'   =>  '* * * * *',
            'route'     =>  'test/second'
        ]
    ],

    /**
     * 文件服务器
     */
    \console\models\popo\Task::SERVER_FILE=>[
        'testMinute'    =>  [
            'pattern' => '* * * * *',
            'route'   => 'test/index'
        ],
        'testSecond'    =>  [
            'pattern'   =>  '* * * * *',
            'route'     =>  'test/second'
        ]
    ],

];