<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index() {

        $projects = auth()->user()->projects;
    
        return view('projects.index', compact('projects'));

    }

    public function show(Project $project) {

        $this->authorize('update', $project); //policy
        // if(auth()->user()->isNot($project->owner)) { 
        //     abort(403);
        // }

        return view('projects.show', compact('project'));
        

    }

    public function create() {

        return view('projects.create');


    }

    public function store() {

        $attributes = $this->validateRequest();

        //$attributes['owner_id'] = auth()->id();

        $project = auth()->user()->projects()->create($attributes); // with this function the owner id will be set automatically

        return redirect($project->path());

    }

    public function edit(Project $project) {

        return view ('projects.edit', compact('project'));

    }

    public function update(Project $project) {
        
        $this->authorize('update', $project); //policy
        
        $attributes = $this->validateRequest();
        
        // if(auth()->user()->isNot($project->owner)) { 
        //     abort(403);
        // }

        $project->update($attributes);

        return redirect ($project->path());

    }

    protected function validateRequest() {

        return request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable'
        ]);

    }


}
