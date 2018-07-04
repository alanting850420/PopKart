<?php

namespace App\Admin\Controllers\Web;

use App\Admin\Controllers\Traits\ModelForm;
use App\Admin\Facades\Admin;
use App\Admin\Form;
use App\Admin\Grid;
use App\Admin\Layout\Content;
use App\Models\WebCarousel;
use Illuminate\Routing\Controller;

class WebCarouselController extends Controller
{
    use ModelForm;

    protected function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Carouserl');
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
            $content->header('Carouserl');
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
            $content->header('Carouserl');
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
        return Admin::grid(WebCarousel::class, function (Grid $grid) {

            $grid->model()->ordered();
            $grid->picture('圖片')->image();
            $grid->url(trans('admin.url'))->display(function ($_url) {
                if (url()->isValidUrl($_url)) {
                    $url = $_url;
                } else {
                    $url = $this->http_url . $_url;
                }
                return "&nbsp;&nbsp;&nbsp;<a href=\"$url\" class=\"dd-nodrag\">$url</a>";
            });
            $grid->order('排序')->orderable();
            $states = [
                'on' => ['text' => '開啟'],
                'off' => ['text' => '關閉'],
            ];
            $grid->column('display','是否顯示')->switch($states);
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
        return WebCarousel::form(function (Form $form) {
            $form->image('picture', '圖片')->rules('required|dimensions:ratio=57/28')->help('圖片比例限制為1140:560 (寬:高)');
            $form->text('url', trans('admin.url'));
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
