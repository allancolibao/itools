<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class F11 extends Model
{
    /**
     * Name of the connection.
     *
     * @var array
     */
    protected $connection = 'sqlite2';
    
     /**
     * Name of the table.
     *
     * @var array
     */
    protected $table = 'f11';
}
