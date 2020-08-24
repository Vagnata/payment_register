<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed notification_status
 */
class Notification extends Model
{
    protected $fillable = ['user_id', 'message', 'notification_status'];
}
