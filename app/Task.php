<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    
    protected $guarded = [];

    protected $touches = ['project'];//references any belongsTo relationships

    public function project() {

        return $this->belongsTo('App\Project');

    }

    public function path() {

        return "/projects/{$this->project->id}/tasks/{$this->id}";

    }

}
