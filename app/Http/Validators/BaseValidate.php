<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

/**
 * 扩展验证器
 *
 * Class BaseValidate
 *
 * @package App\Http\Requests
 */
class BaseValidate
{
    /**
     * 当前验证规则
     * @var array
     */
    protected $rule = [];

    /**
     * 验证提示信息
     * @var array
     */
    protected $message = [];

    /**
     * 验证场景定义
     * @var array
     */
    protected $scene = [];

    /**
     * 设置当前验证场景
     * @var string
     */
    protected $currentScene;

    /**
     * 验证失败错误信息
     * @var string
     */
    protected $error;

    /**
     * 场景需要验证的规则
     * @var array
     */
    protected $only = [];

    /**
     * 设置验证场景
     *
     * @param string $name 场景名
     * @return $this
     */
    public function scene($name)
    {
        $this->currentScene = $name;
        return $this;
    }

    /**
     * 数据验证
     *
     * @param array $data 数据
     * @param array $rules 验证规则
     * @param array $message 自定义验证信息
     * @param string $scene 验证场景
     * @return bool
     */
    public function check($data, $rules = [], $message = [], $scene = '')
    {
        $this->error = '';

        // 验证规则
        $rules = !empty($rules) ?: $this->rule;

        // 自定义验证信息
        $message = !empty($message) ?: $this->message;

        // 读取场景
        if (!$this->getScene($scene)) {
            return false;
        }

        // 如果场景需要验证的规则不为空
        if (!empty($this->only)) {
            $new_rules = [];
            foreach ($this->only as $key => $value) {
                if (array_key_exists($value, $rules)) {
                    $new_rules[$value] = $rules[$value];
                }
            }
            $rules = $new_rules;
        }

        $validator = Validator::make($data, $rules, $message);

        // 验证失败
        if ($validator->fails()) {
            $this->error = $validator->errors()->first();
            return false;
        }

        return true;
    }

    /**
     * 获取数据验证的场景
     *
     * @param string $scene 验证场景
     * @return bool
     */
    protected function getScene($scene = '')
    {
        if (empty($scene)) {
            $scene = $this->currentScene;
        }

        $this->only = [];

        if (empty($scene)) return true;

        if (!isset($this->scene[$scene])) {
            $this->error = "scene:{$scene} is not found";
            return false;
        }

        // 如果设置了验证适用场景
        $this->only = $this->scene[$scene];
        return true;
    }

    /**
     * 获取错误信息
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
