<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminConfig extends Model
{
    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_config');

        parent::__construct($attributes);
    }
}
