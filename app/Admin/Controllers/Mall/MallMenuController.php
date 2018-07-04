<?php

namespace App\Admin\Controllers\Mall;

use App\Admin\Controllers\Traits\ModelForm;
use App\Admin\Facades\Admin;
use App\Admin\Form;
use App\Admin\Layout\Column;
use App\Admin\Layout\Content;
use App\Admin\Layout\Row;
use App\Admin\Tree;
use App\Admin\Widgets\Box;
use App\Models\MallMenu;
use Illuminate\Routing\Controller;

class MallMenuController extends Controller
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
            $content->header('商城Menu');
            $content->description(trans('admin.list'));

            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \App\Admin\Widgets\Form();
                    $form->action(admin_base_path('mall/menu'));
                    $form->select('parent_id', trans('admin.parent_id'))->options(MallMenu::selectOptions());
                    $form->text('title', trans('admin.title'))->rules('required');
                    $form->image('icon', trans('admin.icon'))->rules('dimensions:ratio=1')->help('Icon比例限制為1:1');
                    $form->switch('display', '是否顯示')->states(['on' => ['value' => 1, 'text' => '顯示', 'color' => 'success'], 'off' => ['value' => 0, 'text' => '隱藏', 'color' => 'default']]);
                    $form->switch('shopping', '是否可購買')->states(['on' => ['value' => 1, 'text' => '可購買', 'color' => 'success'], 'off' => ['value' => 0, 'text' => '不可購買', 'color' => 'default']]);
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
                });
            });
        });
    }

    /**
     * Edit interface.
     *
     * @param string $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('商城Menu');
            $content->description(trans('admin.edit'));

            $content->row($this->form()->edit($id));
        });
    }

    /**
     * @return \App\Admin\Tree
     */
    protected function treeView()
    {
        return MallMenu::tree(function (Tree $tree) {
            $tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = "&nbsp;<strong>{$branch['title']}</strong>";
                if($branch['display'])
                    $payload .= '&nbsp;&nbsp;&nbsp;<span class="label label-success">顯示</span>';
                else
                    $payload .= '&nbsp;&nbsp;&nbsp;<span class="label label-default">隱藏</span>';
                if($branch['shopping'])
                    $payload .= '&nbsp;&nbsp;&nbsp;<span class="label label-success">可購買</span>';
                else
                    $payload .= '&nbsp;&nbsp;&nbsp;<span class="label label-default">不可購買</span>';

                return $payload;
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return MallMenu::form(function (Form $form) {
            $form->select('parent_id', trans('admin.parent_id'))->options(MallMenu::selectOptions());
            $form->text('title', trans('admin.title'))->rules('required');
            $form->image('icon', trans('admin.icon'))->rules('dimensions:ratio=1')->help('Icon比例限制為1:1');
            $form->switch('display', '是否顯示')->states(['on' => ['value' => 1, 'text' => '顯示', 'color' => 'success'], 'off' => ['value' => 0, 'text' => '隱藏', 'color' => 'default']]);
            $form->switch('shopping', '是否可購買')->states(['on' => ['value' => 1, 'text' => '可購買', 'color' => 'success'], 'off' => ['value' => 0, 'text' => '不可購買', 'color' => 'default']]);
            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        });
    }
}
