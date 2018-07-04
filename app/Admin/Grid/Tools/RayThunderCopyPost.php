<?php

namespace App\Admin\Grid\Tools;


use App\Admin\Grid\Tools\BatchAction;

class RayThunderCopyPost extends BatchAction
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
        url: '{$this->resource}/copy',
        data: {
            _token:'{$this->getToken()}',
            ids: selectedRows(),
            action: {$this->action}
        },
        success: function (data) {
            $.pjax.reload('#pjax-container');
            var selected = [];
            $('.grid-row-checkbox:checked').each(function(){
                selected.push($(this).data('id'));
            });

            toastr.success(selected);
        }
    });
});

EOT;

    }
}
