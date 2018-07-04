<?php

namespace App\Admin\Controllers\Log;

use App\Models\Administrator;
use App\Models\AdminOperationLog;
use App\Admin\Facades\Admin;
use App\Admin\Grid;
use App\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class OperationLogController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin.operation_log'));
            $content->description(trans('admin.list'));

            $grid = Admin::grid(AdminOperationLog::class, function (Grid $grid) {
                $grid->model()->orderBy('id', 'DESC');

                $grid->id('ID')->sortable();
                $grid->user()->name('User');
                $grid->method()->display(function ($method) {
                    $color = array_get(AdminOperationLog::$methodColors, $method, 'grey');

                    return "<span class=\"badge bg-$color\">$method</span>";
                });
                $grid->path()->label('info');
                $grid->ip()->label('primary');
                $grid->input()->display(function ($input) {
                    $input = json_decode($input, true);
                    $input = array_except($input, ['_pjax', '_token', '_method', '_previous_']);
                    if (empty($input)) {
                        return '<code>{}</code>';
                    }

                    return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>';
                });

                $grid->created_at(trans('admin.created_at'));

                $grid->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->disableEdit();
                });

                $grid->filter(function ($filter) {
                    $filter->equal('user_id', 'User')->select(Administrator::all()->pluck('name', 'id'));
                    $filter->equal('method')->select(array_combine(AdminOperationLog::$methods, AdminOperationLog::$methods));
                    $filter->like('path');
                    $filter->equal('ip');
                });

                $grid->disableCreateButton();
            });

            $content->body($grid);
        });
    }

    public function destroy($id)
    {
        $ids = explode(',', $id);

        if (AdminOperationLog::destroy(array_filter($ids))) {
            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ]);
        }
    }
}
