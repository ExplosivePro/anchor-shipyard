<?php

namespace CapeAndBay\Shipyard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class PushNotifications extends Model
{
    use SoftDeletes, Uuid;

    protected $table, $connection;

    protected $casts = [
        'uuid'=> 'array',
        'data'=> 'array'
    ];

    public function __construct()
    {
        $this->table = config('shipyard.push_notifications.db_table_name');
        $this->connection = config('shipyard.push_notifications.db_connection');
    }

    public function insertNew($data = [])
    {
        $results = false;

        $model = new $this;

        foreach($data as $col => $val)
        {
            $model->$col = $val;
        }

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }
}
