<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formlist extends Model
{
    /**
     * Name of the connection.
     *
     * @var array
     */
    protected $connection = 'sqlite';


     /**
     * Name of the table.
     *
     * @var array
     */
    protected $table = 'form_list';
}
