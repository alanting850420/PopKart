<?php

namespace App\Models;

use App\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLoginLog extends Model
{
    protected $table = 'admin_login_log';

    protected $fillable = ['user_id', 'ip', 'as', 'city', 'country', 'countryCode', 'isp', 'lat', 'lon', 'org', 'region', 'regionName', 'timezone'];

    public static function log($ip)
    {
        $json = file_get_contents('http://ip-api.com/json/' . $ip);
        $info = json_decode($json, true);

        if (!is_array($info)) {
            return;
        }
        $log = new static();
        $log->user_id = Admin::user()->id;
        $log->ip = $ip;
        if (array_get($info, 'status') == "success") {
            $log->as = array_get($info, 'as');
            $log->city = array_get($info, 'city');
            $log->country = array_get($info, 'country');
            $log->countryCode = array_get($info, 'countryCode');
            $log->region = array_get($info, 'region');
            $log->regionName = array_get($info, 'regionName');
            $log->lat = array_get($info, 'lat');
            $log->lon = array_get($info, 'lon');
            $log->timezone = array_get($info, 'timezone');
            $log->isp = array_get($info, 'isp');
            $log->org = array_get($info, 'org');
        }
        return $log->save();
    }

    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(Administrator::class);
    }
}
