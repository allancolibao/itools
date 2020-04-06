<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listings extends Model
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
    protected $table = 'localarea_listings';
}
