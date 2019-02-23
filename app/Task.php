<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'owner_id', 'status_id', 'due_at', 'completed_at'
    ];

    protected $dates = [
        'due_at'
    ];

    public function path()
    {
        return '/api/tasks/' . $this->id;
    }

    public function folders()
    {
        return $this->belongsToMany('App\Folder');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function complete()
    {
        $this->update(['completed_at' => $this->freshTimestampString()]);
    }

    public function uncomplete()
    {
        $this->update(['completed_at' => null]);
    }
}
