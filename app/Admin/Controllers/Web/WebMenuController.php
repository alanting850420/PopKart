<?php

namespace App\Admin\Controllers\Web;

use App\Admin\Controllers\Traits\ModelForm;
use App\Admin\Facades\Admin;
use App\Admin\Form;
use App\Admin\Layout\Column;
use App\Admin\Layout\Content;
use App\Admin\Layout\Row;
use App\Admin\Tree;
use App\Admin\Widgets\Box;
use App\Models\WebMenu;
use Illuminate\Routing\Controller;

class WebMenuController extends Controller
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
            $content->header(trans('admin.menu'));
            $content->description(trans('admin.list'));

            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \App\Admin\Widgets\Form();
                    $form->action(admin_base_path('web/menu'));
                    $form->select('parent_id', trans('admin.parent_id'))->options(WebMenu::selectOptions());
                    $form->text('title_en', '英文' .trans('admin.title'));
                    $form->text('title', trans('admin.title'))->rules('required');
                    $form->text('url', trans('admin.url'));
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
            $content->header(trans('admin.menu'));
            $content->description(trans('admin.edit'));

            $content->row($this->form()->edit($id));
        });
    }

    /**
     * @return \App\Admin\Tree
     */
    protected function treeView()
    {
        return WebMenu::tree(function (Tree $tree) {
            $tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = "&nbsp;<strong style='text-transform:uppercase;'>{$branch['title_en']}&nbsp;{$branch['title']}</strong>";

                if (!isset($branch['children'])) {
                    if (url()->isValidUrl($branch['url'])) {
                        $url = $branch['url'];
                    } else {
                        $url = web_base_path($branch['url']);
                    }

                    $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$url\" class=\"dd-nodrag\">$url</a>";
                }

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
        return WebMenu::form(function (Form $form) {
            $form->select('parent_id', trans('admin.parent_id'))->options(WebMenu::selectOptions());
            $form->text('title_en', '英文' .trans('admin.title'));
            $form->text('title', trans('admin.title'))->rules('required');
            $form->text('url', trans('admin.url'));
            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        });
    }
}