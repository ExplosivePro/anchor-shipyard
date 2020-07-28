<?php

namespace CapeAndBay\Shipyard;

use App\User;
use Illuminate\Notifications\Notifiable;

class PushNotifiables extends User
{
    protected $table = 'users';
}
