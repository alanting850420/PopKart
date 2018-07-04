<?php

namespace App\Admin\Form\Field;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image extends File
{
    use ImageField;

    /**
     * {@inheritdoc}
     */
    protected $view = 'admin::form.file';

    /**
     *  Validation rules.
     *
     * @var string
     */
    protected $rules = 'image';

    /**
     * @param array|UploadedFile $image
     *
     * @return string
     */
    public function prepare($image)
    {
        if (request()->has(static::FILE_DELETE_FLAG)) {
            return $this->destroy();
        }
        if(array_key_exists('_file_del_', $image)){
            foreach ($image['_file_del_'] as $del){
                $files = $this->original ?: [];
                $file = array_get($files, $del);
                if ($this->storage->exists($file)) {
                    $this->storage->delete($file);
                }
                if ($this->storage->exists('small_' .$file)) {
                    $this->storage->delete('small_' .$file);
                }
                if ($this->storage->exists('thn_' .$file)) {
                    $this->storage->delete('thn_' .$file);
                }
            }
            unset($image['_file_del_']);
        }
        if(count($image) == 0){
            return null;
        }

        $this->name = $this->getStoreName($image);

        $this->callInterventionMethods($image->getRealPath());

        return $this->uploadAndDeleteOriginal($image);
    }
}
