<?php

namespace App\Admin\Form\Field;

use App\Admin\Form\Field;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image as InterventionImage;

class File extends Field
{
    use UploadField;

    /**
     * Css.
     *
     * @var array
     */
    protected static $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.min.css',
        //'/vendor/laravel-admin/bootstrap-fileinput/css/fileinput.min.css?v=4.3.7',
    ];

    /**
     * Js.
     *
     * @var array
     */
    protected static $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/plugins/piexif.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/plugins/sortable.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/plugins/purify.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/locales/zh-TW.min.js',
        //'/vendor/laravel-admin/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js?v=4.3.7',
        //'/vendor/laravel-admin/bootstrap-fileinput/js/fileinput.min.js?v=4.3.7',
    ];

    /**
     * Create a new File instance.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->initStorage();

        parent::__construct($column, $arguments);
    }

    /**
     * Default directory for file to upload.
     *
     * @return mixed
     */
    public function defaultDirectory()
    {
        return config('admin.upload.directory.file');
    }

    /**
     * {@inheritdoc}
     */
    public function getValidator(array $input)
    {
        if (request()->has(static::FILE_DELETE_FLAG)) {
            return false;
        }

        if ($this->validator) {
            return $this->validator->call($this, $input);
        }

        /*
         * If has original value, means the form is in edit mode,
         * then remove required rule from rules.
         */
        if ($this->original()) {
            $this->removeRule('required');
        }

        /*
         * Make input data validatable if the column data is `null`.
         */
        if (array_has($input, $this->column) && is_null(array_get($input, $this->column))) {
            $input[$this->column] = '';
        }

        $rules = $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        $rules[$this->column] = $fieldRules;
        $attributes[$this->column] = $this->label;

        return Validator::make($input, $rules, $this->validationMessages, $attributes);
    }

    /**
     * Prepare for saving.
     *
     * @param UploadedFile|array $file
     *
     * @return mixed|string
     */
    public function prepare($file)
    {
        if (request()->has(static::FILE_DELETE_FLAG)) {
            return $this->destroy();
        }

        $this->name = $this->getStoreName($file);

        return $this->uploadAndDeleteOriginal($file);
    }

    /**
     * Upload file and delete original file.
     *
     * @param UploadedFile $file
     *
     * @return mixed
     */
    protected function uploadAndDeleteOriginal(UploadedFile $file)
    {
        $this->renameIfExists($file);

        $img = InterventionImage::make($file)->resize(200, null, function ($constraint) {$constraint->aspectRatio();});
        $this->storage->put('small_' .$this->getDirectory() .'/' .$this->name, (string) $img->encode(), 'public');
        $img = InterventionImage::make($file)->resize(50, null, function ($constraint) {$constraint->aspectRatio();});
        $this->storage->put('thn_' .$this->getDirectory() .'/' .$this->name, (string) $img->encode(), 'public');
        $path = $this->storage->putFileAs($this->getDirectory(), $file, $this->name);

        $this->destroy();

        return $path;
    }

    /**
     * Preview html for file-upload plugin.
     *
     * @return string
     */
    protected function preview()
    {
        return $this->objectUrl($this->value);
    }

    /**
     * Initialize the caption.
     *
     * @param string $caption
     *
     * @return string
     */
    protected function initialCaption($caption)
    {
        return basename($caption);
    }

    /**
     * @return array
     */
    protected function initialPreviewConfig()
    {
        return [
            ['caption' => basename($this->value), 'url'=> '/api/file/delete', 'key' => 0],
        ];
    }

    /**
     * Render file upload field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->removable();
        $this->setupDefaultOptions();

        if (!empty($this->value)) {
            $this->attribute('data-initial-preview', $this->preview());
            $this->attribute('data-initial-caption', $this->initialCaption($this->value));

            $this->setupPreviewOptions();
        }

        $this->options(['overwriteInitial' => true]);

        $options = json_encode($this->options);

        $this->script = <<<EOT

$("input{$this->getElementClassSelector()}").fileinput({$options}).on('filebeforedelete', function(e, params) {
        $("input{$this->getElementClassSelector()}").parent("div").append("<input type='hidden' name='_file_del_[]' value= '{$this->column},"+params+"' />");
    });;

EOT;

        return parent::render();
    }
}
