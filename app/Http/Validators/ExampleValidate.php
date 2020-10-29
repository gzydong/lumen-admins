<?php

namespace App\Http\Validators;

class ExampleValidate extends BaseValidate
{
    // 验证规则
    protected $rule = [
        'article_id' => 'required|Integer|min:0',
        'class_id' => 'required|Integer|min:0',
        'title' => 'required|max:255',
        'content' => 'required',
        'md_content' => 'required',
    ];

    // 自定义验证信息
    protected $message = [
        'article_id.required' => '缺少 article_id 字段',
        'class_id.required' => '缺少 class_id 字段',
        'title.required' => '缺少 title 字段',
        'content.required' => '缺少 content 字段',
        'md_content.required' => '缺少 md_content 字段',
    ];

    // 场景规则
    protected $scene = [
        'add' => ['article_id', 'class_id', 'title', 'content', 'md_content'],
        'delete' => ['article_id','content']
    ];
}
