<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
	public function user()
	{
		return $this->belongsTo('App\User', 'owner_id');
	}

	public function tasks()
	{
		return $this->hasMany('App\Task');
	}
}
