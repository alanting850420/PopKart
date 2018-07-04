<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\Traits\ModelForm;
use App\Admin\Grid;
use App\Models\AdminConfig;
use App\Admin\Facades\Admin;
use App\Admin\Form;
use App\Admin\Layout\Content;

class HomeController
{
    use ModelForm;
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return view('home')->with('content', Admin::grid(AdminConfig::class, function (Grid $grid) {
            $grid->name('參數')->display(function ($name) {
                return "<a tabindex=\"0\" class=\"btn btn-xs btn-twitter\" role=\"button\" data-toggle=\"popover\" data-html=true title=\"Usage\" data-content=\"<code>config('$name');</code>\">$name</a>";
            });
            $grid->value('數值');
            $grid->description('說明')->editable();
            $grid->created_at(trans('admin.created_at'));
            $grid->updated_at(trans('admin.updated_at'));

            // 篩選設定
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('name', '參數');
            });

            // 操作設定
            $grid->actions(function (Grid\Displayers\Actions $actions) {

            });

            // 工具設定
            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
            $grid->disableExport();
        })->render());
    }
    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('Config');
            $content->description(trans('admin.create'));
            $content->body($this->form());
        });
    }
    public function form()
    {
        return Admin::form(AdminConfig::class, function (Form $form) {
            $form->text('name', '參數')->rules('required');
            $form->textarea('value', '數值')->rules('required');
            $form->textarea('description', '說明');
            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        });
    }
}

