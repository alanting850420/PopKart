<?php

namespace App\Admin\Form\Field;

use App\Admin\Form\Field;

/**
 * Class ListBox.
 *
 * @see https://github.com/istvan-ujjmeszaros/bootstrap-duallistbox
 */
class Attribute extends Field
{
    protected $view = 'admin.form.attributes';

    public function prepare($value)
    {
        $_value = [];
        foreach ($value as $v){
            if(!empty($v['name'])){
                $_value[] = $v;
            }
        }
        return $_value;
    }

    public function render()
    {
        return parent::render();
    }
}
