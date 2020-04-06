<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
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
    protected $table = 'action_log';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_cat', 'performed_by', 'old_info', 'new_info', 'on_table', 'from_researcher','action_taken'
    ];
}
