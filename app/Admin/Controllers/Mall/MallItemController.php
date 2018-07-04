<?php

namespace App\Admin\Controllers\Mall;

use App\Admin\Controllers\Traits\ModelForm;
use App\Admin\Grid;
use App\Admin\Facades\Admin;
use App\Admin\Form;
use App\Admin\Layout\Content;
use App\Models\MallItem;

class MallItemController
{
    use ModelForm;
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('商城商品');
            $content->description(trans('admin.list'));
            $content->body($this->grid());
        });
    }
    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('商城商品');
            $content->description(trans('admin.edit'));
            $content->body($this->form()->edit($id));
        });
    }
    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('商城商品');
            $content->description(trans('admin.create'));
            $content->body($this->form());
        });
    }
    public function grid()
    {
        return Admin::grid(MallItem::class, function (Grid $grid) {
            $grid->code('商品編號');
            $grid->picture('封面照')->image();

            $grid->title('標題');
            $grid->original_price('原價')->editable();
            $grid->price('網路價')->editable();
            $grid->stock('庫存量')->editable();

            $states = [
                'on' => ['text' => '開啟'],
                'off' => ['text' => '關閉'],
            ];

            $grid->column('display','是否顯示')->switch($states);
            $grid->column('shopping','是否可購買')->switch($states);
            /*
            $grid->column('item_tag','商品標籤')->switchGroup([
                'recommend' => '推薦', 'hot' => '熱門', 'new' => '最新'
            ], $states);
            */
            $grid->created_at(trans('admin.created_at'));
            $grid->updated_at(trans('admin.updated_at'));

            // 篩選設定
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('code', '商品編號');
                $filter->like('title', '標題');
            });

            // 操作設定
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                //$actions->prepend('<a href="' .config('url') .'/admin/worker?miner_id=' .$this->getKey().'"><i class="fa fa-external-link"></i></a>');
            });

            // 工具設定
            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

        });
    }
    public function form()
    {
        return Admin::form(MallItem::class, function (Form $form) {
            $form->tab('基本', function (Form $form) {
                $states = [
                    'on' => ['value' => 1, 'text' => '開啟', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '關閉', 'color' => 'default'],
                ];
                $form->text('code', '商品編號');
                $form->text('title', '標題')->rules('required');
                $form->text('tag', 'TAG');
                $form->textarea('content', '內容');
                $form->switch('display', '是否顯示')->states($states);
                $form->display('created_at', trans('admin.created_at'));
                $form->display('updated_at', trans('admin.updated_at'));
                $form->attribute('attribute', '規格');
            })->tab('價格', function (Form $form) {
                $states = [
                    'on' => ['value' => 1, 'text' => '開啟', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '關閉', 'color' => 'default'],
                ];
                $form->number('original_price', '原價');
                $form->number('price', '網路價');
                $form->number('price_1', 'VIP價');
                $form->number('price_2', '金卡價');
                $form->number('price_3', '白金價');
            })->tab('庫存', function (Form $form) {
                $states = [
                    'on' => ['value' => 1, 'text' => '開啟', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '關閉', 'color' => 'default'],
                ];
                $form->number('stock', '庫存量');
                $form->number('buy_limit', '單次購買上限');
                $form->switch('put_switch', '是否設定上架')->states($states);
                $form->datetime('put_at', '上架時間');
                $form->switch('pull_switch', '是否設定下架')->states($states);
                $form->datetime('pull_at', '下架時間');
            })->tab('照片', function (Form $form) {
                $form->image('picture', '封面照')->rules('dimensions:ratio=1')->help('封面照比例限制為1:1 (寬:高)');
                $form->multipleImage('multiple_picture', '商品照片')->options(['showRemove' => true])->rules('dimensions:ratio=1')->help('商品照片比例限制為1:1 (寬:高)(可上傳多張)');
            })->tab('內容', function (Form $form) {
                $form->hasMany('contents',null, function (Form\NestedForm $form) {
                    $states = [
                        'on' => ['value' => 1, 'text' => '開啟', 'color' => 'success'],
                        'off' => ['value' => 0, 'text' => '關閉', 'color' => 'default'],
                    ];
                    $form->text('tab_title', '標題');
                    $form->wangEditor('tab_content', '內容');
                    $form->switch('tab_display', '是否顯示')->states($states);
                });
            });
        });
    }
}
