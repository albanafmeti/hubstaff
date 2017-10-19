<?php

namespace Noisim\Hubstaff\Entities;

use Noisim\Hubstaff\Exceptions\ProjectActionException;

class Project extends Entity
{
    public $id;
    public $name;
    public $last_activity;
    public $status;
    public $description;

    public $members = [];

    function __construct($id = null, $name = null)
    {
        parent::__construct();
        $this->create($id, $name);
    }

    public function create($id = null, $name = null)
    {
        if (is_array($id)) {
            foreach ($id as $field => $value) {
                $this->{$field} = $value;
            }
        } else {
            $this->id = ($id) ? $id : $this->id;
            $this->name = ($name) ? $name : $this->name;
        }
        return $this;
    }

    public function all($queryParams = [])
    {
        $response = $this->request()->get($this->api_url("projects", $queryParams))->send();
        return $this->handle_response($response, new ProjectActionException(), ['projects']);
    }

    public function get($id = null)
    {
        $id = ($id) ? $id : $this->id;

        if (!$id) {
            throw new ProjectActionException("Project ID not specified.");
        }

        $response = $this->request()->get($this->api_url("projects/$id"))->send();
        $project = $this->handle_response($response, new ProjectActionException(), ['project']);
        $this->id = $project->id;
        $this->name = $project->name;
        $this->last_activity = $project->last_activity;
        $this->status = $project->status;
        $this->description = $project->description;
        return $this;
    }

    public function members($id = null, $queryParams = [])
    {
        $id = ($id) ? $id : $this->id;

        if (!$id) {
            throw new ProjectActionException("Project ID not specified.");
        }

        $response = $this->request()->get($this->api_url("projects/$id/members", $queryParams))->send();
        $members = $this->handle_response($response, new ProjectActionException(), ['users']);
        $this->members = $members;
        return $members;
    }
}