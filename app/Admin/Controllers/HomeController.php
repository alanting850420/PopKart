<?php

namespace App\Admin\Controllers;

use App\Admin\Widgets\InfoBox;
use App\Http\Controllers\Controller;
use App\Admin\Facades\Admin;
use App\Admin\Layout\Column;
use App\Admin\Layout\Content;
use App\Admin\Layout\Row;
use App\Models\Account;

class HomeController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('雷神後台管理平台資訊');
            $content->description('Dashboard');

            $content->row(function ($row) {
                $row->column(4, new InfoBox('未上架', 'users', 'green', '/admin/account', account::where('狀態',1)->count()));
                $row->column(4, new InfoBox('上架中', 'comments', 'red', '/admin/account',  account::where('狀態',2)->count()));
                $row->column(4, new InfoBox('已售出', 'shopping-basket', 'aqua', '/admin/account',  account::where('狀態',3)->count()));
            });

            $content->row(function (Row $row) {
                $row->column(3,function(){});
                $row->column(6, function (Column $column) {
                    $column->append($this::膠囊帳());
                });

                /*$row->column(6, function (Column $column) {
                    $column->append($this::dependencies());
                });*/
            });
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function 膠囊帳(){
        $envs = [
            ['name' => '遊俠','value' => account::where('狀態',1)->where('遊俠','>','0')->count(). ' 隻'],
            ['name' => '魔光','value' => account::where('狀態',1)->where('遊俠','=','0')->where('魔光','>','0')->count(). ' 隻'],
            ['name' => '黑騎','value' => account::where('狀態',1)->where('遊俠','=','0')->where('魔光','=','0')->where('黑騎','>','0')->count(). ' 隻'],
            ['name' => '富豪','value' => account::where('狀態',1)->where('遊俠','=','0')->where('魔光','=','0')->where('黑騎','=','0')->where('富豪','=','0')->count(). ' 隻'],
            ['name' => '犽霸','value' => account::where('狀態',1)->where('遊俠','=','0')->where('魔光','=','0')->where('黑騎','=','0')->where('富豪','=','0')->where('犽霸','>','0')->count(). ' 隻'],
            ['name' => '舒適','value' => account::where('狀態',1)->where('舒適','>','0')->count(). ' 隻'],
        ];

        return view('admin::dashboard.environment', compact('envs'));
    }
    public static function environment()
    {
        $envs = [
            ['name' => 'PHP version',       'value' => 'PHP/'.PHP_VERSION],
            ['name' => 'Laravel version',   'value' => app()->version()],
            ['name' => 'CGI',               'value' => php_sapi_name()],
            ['name' => 'Uname',             'value' => php_uname()],
            ['name' => 'Server',            'value' => array_get($_SERVER, 'SERVER_SOFTWARE')],

            ['name' => 'Cache driver',      'value' => config('cache.default')],
            ['name' => 'Session driver',    'value' => config('session.driver')],
            ['name' => 'Queue driver',      'value' => config('queue.default')],

            ['name' => 'Timezone',          'value' => config('app.timezone')],
            ['name' => 'Locale',            'value' => config('app.locale')],
            ['name' => 'Env',               'value' => config('app.env')],
            ['name' => 'URL',               'value' => config('app.url')],
        ];

        return view('admin::dashboard.environment', compact('envs'));
    }

    public static function dependencies()
    {
        $extensions = [
            'helpers' => [
                'name' => 'laravel-admin-ext/helpers',
                'link' => 'https://github.com/laravel-admin-extensions/helpers',
                'icon' => 'gears',
            ],
            'log-viewer' => [
                'name' => 'laravel-admin-ext/log-viewer',
                'link' => 'https://github.com/laravel-admin-extensions/log-viewer',
                'icon' => 'database',
            ],
            'backup' => [
                'name' => 'laravel-admin-ext/backup',
                'link' => 'https://github.com/laravel-admin-extensions/backup',
                'icon' => 'copy',
            ],
            'config' => [
                'name' => 'laravel-admin-ext/config',
                'link' => 'https://github.com/laravel-admin-extensions/config',
                'icon' => 'toggle-on',
            ],
            'api-tester' => [
                'name' => 'laravel-admin-ext/api-tester',
                'link' => 'https://github.com/laravel-admin-extensions/api-tester',
                'icon' => 'sliders',
            ],
            'media-manager' => [
                'name' => 'laravel-admin-ext/media-manager',
                'link' => 'https://github.com/laravel-admin-extensions/media-manager',
                'icon' => 'file',
            ],
            'scheduling' => [
                'name' => 'laravel-admin-ext/scheduling',
                'link' => 'https://github.com/laravel-admin-extensions/scheduling',
                'icon' => 'clock-o',
            ],
            'reporter' => [
                'name' => 'laravel-admin-ext/reporter',
                'link' => 'https://github.com/laravel-admin-extensions/reporter',
                'icon' => 'bug',
            ],
            'translation' => [
                'name' => 'laravel-admin-ext/translation',
                'link' => 'https://github.com/laravel-admin-extensions/translation',
                'icon' => 'language',
            ],
        ];

        foreach ($extensions as &$extension) {
            $name = explode('/', $extension['name']);
            $extension['installed'] = array_key_exists(end($name), $extensions);
        }

        return view('admin::dashboard.extensions', compact('extensions'));
    }
}
