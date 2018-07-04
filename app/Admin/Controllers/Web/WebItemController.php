<?php

namespace App\Admin\Controllers\Web;

use App\Admin\Controllers\Traits\ModelForm;
use App\Admin\Facades\Admin;
use App\Admin\Form;
use App\Admin\Grid;
use App\Admin\Layout\Content;
use App\Models\WebItem;
use Illuminate\Routing\Controller;

class WebItemController extends Controller
{
    use ModelForm;

    protected function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('最新消息');
            $content->description(trans('admin.list'));
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('最新消息');
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
            $content->header('最新消息');
            $content->description(trans('admin.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(WebItem::class, function (Grid $grid) {

            $grid->picture('封面照')->image();
            $grid->title('標題');
            $states = [
                'on' => ['text' => '開啟'],
                'off' => ['text' => '關閉'],
            ];
            $grid->column('display','是否顯示')->switch($states);

            $grid->put_at('發布時間');
            $grid->created_at(trans('admin.created_at'));
            $grid->updated_at(trans('admin.updated_at'));
            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return WebItem::form(function (Form $form) {
            $form->text('title', '標題')->rules('required');
            $form->text('tag', 'TAG');
            $form->image('picture', '封面照')->rules('required|dimensions:ratio=57/28')->help('封面照比例限制為1140:560 (寬:高)');
            $form->wangEditor('content', '內容');
            $states = [
                'on' => ['value' => 1, 'text' => '開啟', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '關閉', 'color' => 'default'],
            ];
            $form->switch('display', '是否顯示')->states($states);
            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
            $form->disableReset();
        });
    }

}
