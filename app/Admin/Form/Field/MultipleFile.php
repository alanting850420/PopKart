<?php

namespace App\Admin\Form\Field;

use App\Admin\Form\Field;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MultipleFile extends Field
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

        $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        $attributes[$this->column] = $this->label;

        list($rules, $input) = $this->hydrateFiles(array_get($input, $this->column, []));

        return Validator::make($input, $rules, $this->validationMessages, $attributes);
    }

    /**
     * Hydrate the files array.
     *
     * @param array $value
     *
     * @return array
     */
    protected function hydrateFiles(array $value)
    {
        if (empty($value)) {
            return [[$this->column => $this->getRules()], []];
        }

        $rules = $input = [];

        foreach ($value as $key => $file) {
            $rules[$this->column] = $this->getRules();
            $input[$this->column] = $file;
        }

        return [$rules, $input];
    }

    /**
     * Prepare for saving.
     *
     * @param UploadedFile|array $files
     *
     * @return mixed|string
     */
    public function prepare($files)
    {
        if (request()->has(static::FILE_DELETE_FLAG)) {
            return $this->destroy(request(static::FILE_DELETE_FLAG));
        }

        if(array_key_exists('_file_del_', $files)){
            foreach ($files['_file_del_'] as $del){
                $this->destroy($del);
                unset($this->original[$del]);
            }
            unset($files['_file_del_']);
        }
        $targets = array_map([$this, 'prepareForeach'], $files);

        return array_merge($this->original(), $targets);
    }

    /**
     * @return array|mixed
     */
    public function original()
    {
        if (empty($this->original)) {
            return [];
        }

        return $this->original;
    }

    /**
     * Prepare for each file.
     *
     * @param UploadedFile $file
     *
     * @return mixed|string
     */
    protected function prepareForeach(UploadedFile $file = null)
    {
        $this->name = $this->getStoreName($file);

        return tap($this->upload($file), function () {
            $this->name = null;
        });
    }

    /**
     * Preview html for file-upload plugin.
     *
     * @return array
     */
    protected function preview()
    {
        $files = $this->value ?: [];

        return array_map([$this, 'objectUrl'], $files);
    }

    /**
     * Initialize the caption.
     *
     * @param array $caption
     *
     * @return string
     */
    protected function initialCaption($caption)
    {
        if (empty($caption)) {
            return '';
        }

        $caption = array_map('basename', $caption);

        return implode(',', $caption);
    }

    /**
     * @return array
     */
    protected function initialPreviewConfig()
    {
        $files = $this->value ?: [];

        $config = [];

        foreach ($files as $index => $file) {
            $config[] = [
                'caption' => basename($file),
                'url'=> '/api/file/delete',
                'key'     => $index,
            ];
        }

        return $config;
    }

    /**
     * Render file upload field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->attribute('multiple', true);

        $this->removable();
        $this->setupDefaultOptions();

        if (!empty($this->value)) {
            $this->options(['initialPreview' => $this->preview()]);
            $this->setupPreviewOptions();
        }

        $options = json_encode($this->options);

        $this->script = <<<EOT
$("input{$this->getElementClassSelector()}").fileinput({$options}).on('filebeforedelete', function(e, params) {
        $("input{$this->getElementClassSelector()}").parent("div").append("<input type='hidden' name='_file_del_[]' value= '{$this->column},"+params+"' />");
    });
EOT;

        return parent::render();
    }

    /**
     * Destroy original files.
     *
     * @return string.
     */
    public function destroy($key)
    {
        $files = $this->original ?: [];

        $file = array_get($files, $key);

        if ($this->storage->exists($file)) {
            $this->storage->delete($file);
        }

        if ($this->storage->exists('small_' .$file)) {
            $this->storage->delete('small_' .$file);
        }
        if ($this->storage->exists('thn_' .$file)) {
            $this->storage->delete('thn_' .$file);
        }

        unset($files[$key]);

        return array_values($files);
    }
}
