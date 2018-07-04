<?php

if (!function_exists('admin_path')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('admin.directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('admin_url')) {
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_url($path = '')
    {
        if (\Illuminate\Support\Facades\URL::isValidUrl($path)) {
            return $path;
        }

        return url(admin_base_path($path));
    }
}

if (!function_exists('admin_base_path')) {
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_base_path($path = '')
    {
        $prefix = '/'.trim(config('admin.route.prefix'), '/');

        $prefix = ($prefix == '/') ? '' : $prefix;

        return $prefix.'/'.trim($path, '/');
    }
}

if (!function_exists('web_base_path')) {
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function web_base_path($path = '')
    {
        $prefix = trim(config('official_website'), '/');

        return $prefix.'/'.trim($path, '/');
    }
}

if (!function_exists('admin_toastr')) {

    /**
     * Flash a toastr message bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array  $options
     *
     * @return string
     */
    function admin_toastr($message = '', $type = 'success', $options = [])
    {
        $toastr = new \Illuminate\Support\MessageBag(get_defined_vars());

        \Illuminate\Support\Facades\Session::flash('toastr', $toastr);
    }
}

if (!function_exists('admin_asset')) {

    /**
     * @param $path
     *
     * @return string
     */
    function admin_asset($path)
    {
        return asset($path, config('admin.secure'));
    }
}


function we_id($name){
    $id = str_replace('[', '', $name);
    $id = str_replace(']', '', $id);
    return $id;
}

function we_config($id, $name)
{
    $uploadImgServer = config('wang-editor.uploadImgServer', '/laravel-wang-editor/upload');
    $pasteFilterStyle = config('wang-editor.pasteFilterStyle', 'false');
    $pasteTextHandle = 'function (content) { return content; }';
    if ($pasteFilterStyle == 'false') {
        $pasteTextHandle = config('wang-editor.pasteTextHandle', 'function (content) { return content; }');
    }
    $token = csrf_token();
    $showLinkImg = config('wang-editor.showLinkImg', 'false');
    $uploadImgShowBase64 = config('wang-editor.uploadImgShowBase64', 'false');
    $uploadImgMaxSize = config('wang-editor.uploadImgMaxSize', 5*1024*1024);
    $uploadImgMaxLength = config('wang-editor.uploadImgMaxLength', 5);
    $we_script = <<<EOT
        <script type="text/javascript">
            if(window.wangEditor){
                createEditor('#{$id}', '{$name}', {$pasteFilterStyle}, {$pasteTextHandle}, {$showLinkImg}, {$uploadImgShowBase64}, '{$uploadImgServer}', '{$token}', {$uploadImgMaxSize}, {$uploadImgMaxLength});
            }else{
                $(window).load(function() {
                    createEditor('#{$id}', '{$name}', {$pasteFilterStyle}, {$pasteTextHandle}, {$showLinkImg}, {$uploadImgShowBase64}, '{$uploadImgServer}', '{$token}', {$uploadImgMaxSize}, {$uploadImgMaxLength});
                });
            }
        </script>
EOT;
    return $we_script;
}