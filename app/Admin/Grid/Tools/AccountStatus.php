<?php

namespace App\Admin\Grid\Tools;

use App\Admin\Facades\Admin;
use Illuminate\Support\Facades\Request;

class AccountStatus extends AbstractTool
{
    public function render()
    {
        Admin::script($this->script());

        $options = [
            '0' => 'All',
            '1' => '未上架',
            '2' => '上架中',
            '3' => '已售出',
            '4' => '已改車',
        ];

        return view('admin.grid.tool.account', compact('options'));
    }

    public function script()
    {
        $url = Request::fullUrlWithQuery(['status' => '_status_']);

        return <<<EOT
        
        $('input:radio.simcard-status').change(function () {
        
            var url = "$url".replace('_status_', $(this).val());
        
            $.pjax({container:'#pjax-container', url: url });
        
        });

EOT;
    }

}