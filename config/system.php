<?php

/**
 * 自定义的配置信息
 */
return [
    // SQL查询日志相关配置
    'sql_query_log' => [
        'enabled' => env('SQL_QUERY_LOG', false),   //是否开启
        'slower_than' => env('SQL_QUERY_SLOWER', 0),     //慢查询时间/单位毫秒
    ],
];
