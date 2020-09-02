<?php

namespace CapeAndBay\Shipyard;

use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class PushNotifications extends Model
{
    use SoftDeletes;

    protected $table, $connection;

    protected $casts = [
        'id'=> 'uuid',
        'data'=> 'array'
    ];

    public function __construct()
    {
        parent::__construct();
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
