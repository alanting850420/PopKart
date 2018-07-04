<?php
/**
 * Created by PhpStorm.
 * User: qulam
 * Date: 2018/2/13
 * Time: 下午 10:19
 */

namespace App\Admin\Form\Field;

use App\Admin\Form\Field;

class WangEditor extends Field
{
    protected $view = 'admin.form.wang-editor';
    protected static $css = [
        '/vendor/wangEditor/dist/wangEditor.min.css',
    ];
    protected static $js = [
        '/vendor/wangEditor/dist/wangEditor.min.js',
        '/vendor/wangEditor/dist/wangConfig.js',
    ];
    public function render()
    {
        return parent::render();
    }
}