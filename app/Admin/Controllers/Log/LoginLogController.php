<?php

namespace App\Admin\Controllers\Log;

use App\Models\Administrator;
use App\Admin\Facades\Admin;
use App\Admin\Grid;
use App\Admin\Layout\Content;
use App\Models\AdminLoginLog;
use Illuminate\Routing\Controller;

class LoginLogController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin.login_histroy'));
            $content->description(trans('admin.list'));

            $grid = Admin::grid(AdminLoginLog::class, function (Grid $grid) {
                $grid->model()->orderBy('id', 'DESC');

                $grid->id('ID')->sortable();
                $grid->user()->name('User');
                $grid->ip()->label('primary');
                $grid->country('國家');
                $grid->city('城市');
                $grid->column('position')->openMap(function () {
                    return [$this['lat'], $this['lon']];
                }, '顯示位置');

                $grid->created_at(trans('admin.created_at'));

                $grid->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->disableEdit();
                });

                $grid->filter(function ($filter) {
                    $filter->equal('user_id', 'User')->select(Administrator::all()->pluck('name', 'id'));
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

        if (AdminLoginLog::destroy(array_filter($ids))) {
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
