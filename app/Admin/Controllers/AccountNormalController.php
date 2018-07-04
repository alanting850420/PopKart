<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\Traits\ModelForm;
use App\Admin\Grid\ExcelExpoter;
use App\Admin\Grid\Tools\AccountStatus;
use App\Admin\Grid;
use App\Admin\Facades\Admin;
use App\Admin\Form;
use App\Admin\Grid\Tools\AccountStatusPost;
use App\Admin\Grid\Tools\RayThunderCopyPost;
use App\Admin\Layout\Content;
use App\Models\Account;
use App\Models\AccountNormal;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequet;


class AccountNormalController
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
            $content->header('帳號資訊');
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
            $content->header('帳號資訊');
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
            $content->header('帳號資訊');
            $content->description(trans('admin.create'));
            $content->body($this->form());
        });
    }

    public function status(HttpRequet $request)
    {
        AccountNormal::whereIn('id', $request->get('ids'))->update(['狀態' => $request->get('action')]);
    }

    public function copy(HttpRequet $request)
    {
    }

    public function grid()
    {
        return Admin::grid(AccountNormal::class, function (Grid $grid) {
            $grid->model()->orderBy('name');
            $grid->model()->status(Request::get('status'));
            $grid->編號('編號')->editable();
            $grid->id('帳號')->editable();
            $grid->name('ID')->editable();
            $grid->level('等級')->editable();
            //$grid->黑武神('黑武神')->editable();
            //$grid->金遊('黃金遊俠')->editable();
            //$grid->積巴('積木巴爾')->editable();
            //$grid->巴爾('巴爾扎特')->editable();
            $grid->遊俠('遊俠')->editable();
            $grid->魔光('魔光騎士')->editable();
            $grid->黑騎('黑騎士')->editable();
            //$grid->音速('音速')->editable();
            $grid->富豪('富豪超跑')->editable();
            $grid->犽霸('犽霸')->editable();
            $grid->舒適('舒適')->editable();
            $grid->更名卡('更名卡')->switch(['on' => ['value' => 1, 'text' => '有', 'color' => 'success'], 'off' => ['value' => 0, 'text' => '沒', 'color' => 'default']]);
            $grid->銀色('銀色加工廠')->editable();
            $grid->TOP100('TOP100')->editable();
            $grid->車子數量('車子數量')->editable();
            $grid->Lucci('Lucci')->editable();
            $grid->Koin('Koin')->editable();
            $grid->膠囊R('膠囊R')->editable();
            $grid->膠囊S('膠囊S')->editable();
            $grid->膠囊B('膠囊B')->editable();
            $grid->price('價格')->editable();
            $grid->狀態('狀態')->options()->select([
                1 => '未上架',
                2 => '上架中',
                3 => '已售出',
                4 => '已改車',
            ]);
            //$grid->created_at(trans('admin.created_at'));
            //$grid->updated_at(trans('admin.updated_at'));

            // 篩選設定
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                //$filter->equal('miner_id', 'Miner')->select(Miner::all()->pluck('miner', 'id'));
                $filter->equal('編號', '編號');
                $filter->like('id', '帳號');
                $filter->between('name', 'ID');
                $filter->between('level', '等級');
                //$filter->gt('黑武神', '黑武神')->integer();
                //$filter->gt('金遊', '黃金遊俠')->integer();
                //$filter->gt('積巴', '積木巴爾')->integer();
                $filter->between('遊俠', '遊俠');
                $filter->between('魔光', '魔光騎士');
                $filter->between('黑騎', '黑騎士');
                //$filter->gt('音速', '音速')->integer();
                $filter->between('富豪', '富豪超跑');
                $filter->between('犽霸', '犽霸');
                $filter->between('舒適', '舒適');
                $filter->between('price', '價格');
                $filter->equal('更名卡')->radio([
                    ''   => '不選擇',
                    0    => '無',
                    1    => '有',
                ]);
                $filter->between('銀色', '銀色加工廠');
                $filter->between('TOP100', 'TOP100');
                $filter->gt('車子數量', '車子數量')->integer();
                $filter->gt('Lucci', 'Lucci')->integer();
                $filter->gt('Koin', 'Koin')->integer();
                $filter->between('膠囊R', '膠囊R');
                $filter->gt('膠囊S', '膠囊S')->integer();
                $filter->gt('膠囊B', '膠囊B')->integer();
                $filter->equal('狀態')->radio([
                    ''   => '不選擇',
                    1    => '未上架',
                    2    => '已上架',
                    3    => '已售出',
                    4    => '已改車',
                ]);
            });

            // 操作設定
            $grid->actions(function (Grid\Displayers\Actions $actions) {

            });

            // 工具設定
            $grid->tools(function ($tools) {
                $tools->append(new AccountStatus());
                $tools->batch(function (Grid\Tools\BatchActions $batch) {
                    $batch->disableDelete();
                    $batch->add('未上架', new AccountStatusPost(1));
                    $batch->add('上架中', new AccountStatusPost(2));
                    $batch->add('已售出', new AccountStatusPost(3));
                    $batch->add('已改車', new AccountStatusPost(4));
                    $batch->add('複製',new RayThunderCopyPost(1));
                });
            });

            $grid->exporter(new ExcelExpoter());
            //$grid->disableExport();
        });
    }
    public function form()
    {
        return Admin::form(AccountNormal::class, function (Form $form) {

            $form->text('編號', '編號')->rules('required|regex:/^[A-Za-z0-9]/',[
                'regex' => '必须全部為英文或數字',
            ]);
            $form->display('id', '帳號');
            $form->text('name', 'ID')->rules('required|regex:/^[A-Za-z0-9]/',[
                'regex' => '必须全部為英文或數字',
            ]);
            $form->number('level', '等級');
            //$form->number('黑武神', '黑武神');
            //$form->number('金遊', '黃金遊俠');
            //$form->number('積巴', '積木巴爾');
            //$form->number('巴爾', '巴爾扎特');
            $form->number('遊俠', '遊俠');
            $form->number('魔光', '魔光騎士');
            $form->number('黑騎', '黑騎士');
            //$form->number('音速', '音速');
            $form->number('富豪', '富豪超跑');
            $form->number('犽霸', '犽霸');
            $form->number('舒適', '舒適');
            $form->switch('更名卡', '更名卡')->states(['on' => ['value' => 1, 'text' => '有更名卡', 'color' => 'success'], 'off' => ['value' => 0, 'text' => '沒更名卡', 'color' => 'default']]);
            $form->number('銀色', '銀色加工廠');
            $form->number('TOP100', 'TOP100');
            $form->number('車子數量', '車子數量');
            $form->number('Lucci', 'Lucci');
            $form->number('Koin', 'Koin');
            $form->number('膠囊R', '膠囊R');
            $form->number('膠囊S', '膠囊S');
            $form->number('膠囊B', '膠囊B');
            $form->number('price', '價格');
            $form->select('狀態', '狀態')->options([
                1 => '未上架',
                2 => '上架中',
                3 => '已售出',
                4 => '已改車',
            ])->rules('required');
            $form->text('store', '8591賣場');
            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        });
    }
}
