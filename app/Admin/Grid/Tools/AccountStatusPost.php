<?php

namespace App\Admin\Grid\Tools;


use App\Admin\Grid\Tools\BatchAction;

class AccountStatusPost extends BatchAction
{
    protected $action;

    public function __construct($action)
    {
        $this->action = $action;
    }

    public function script()
    {
        return <<<EOT
        
$('{$this->getElementClass()}').on('click', function() {

    $.ajax({
        method: 'post',
        url: '{$this->resource}/status',
        data: {
            _token:'{$this->getToken()}',
            ids: selectedRows(),
            action: {$this->action}
        },
        success: function (data) {
            $.pjax.reload('#pjax-container');
            toastr.success('操作成功');
        }
    });
});

EOT;

    }
}
