<?php

namespace App\Admin\Grid\Displayers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;

class Image extends AbstractDisplayer
{
    public function display($server = '', $width = 50, $height = 50)
    {
        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        return collect((array) $this->value)->filter()->map(function ($path) use ($server, $width, $height) {
            $path = 'thn_' .$path;
            if (url()->isValidUrl($path)) {
                $src = $path;
            } elseif ($server) {
                $src = $server.$path;
            } else {
                $src = Storage::disk(config('admin.upload.disk'))->url($path);
            }

            return "<img src='$src' style='max-width:{$width}px;max-height:{$height}px' class='img img-thumbnail' />";
        })->implode('&nbsp;');
    }
}
