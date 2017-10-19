<?php

namespace Noisim\Hubstaff\Entities;

use Noisim\Hubstaff\Exceptions\OrganizationActionException;

class Organization extends Entity
{
    public $id;
    public $name;
    public $last_activity;

    public $members = [];
    public $projects = [];

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
        $response = $this->request()->get($this->api_url("organizations", $queryParams))->send();
        return $this->handle_response($response, new OrganizationActionException(), ['organizations']);
    }

    public function get($id = null)
    {
        $id = ($id) ? $id : $this->id;

        if (!$id) {
            throw new OrganizationActionException("Organization ID not specified.");
        }

        $response = $this->request()->get($this->api_url("organizations/$id"))->send();
        $organization = $this->handle_response($response, new OrganizationActionException(), ['organization']);
        $this->id = $organization->id;
        $this->name = $organization->name;
        $this->last_activity = $organization->last_activity;
        return $this;
    }

    public function projects($id = null, $queryParams = [])
    {
        $id = ($id) ? $id : $this->id;

        if (!$id) {
            throw new OrganizationActionException("Organization ID not specified.");
        }

        $response = $this->request()->get($this->api_url("organizations/$id/projects", $queryParams))->send();
        $projects = $this->handle_response($response, new OrganizationActionException(), ['projects']);
        $this->projects = $projects;
        return $projects;
    }

    public function members($id = null, $queryParams = [])
    {
        $id = ($id) ? $id : $this->id;

        if (!$id) {
            throw new OrganizationActionException("Organization ID not specified.");
        }

        $response = $this->request()->get($this->api_url("organizations/$id/members", $queryParams))->send();
        $members = $this->handle_response($response, new OrganizationActionException(), ['users']);
        $this->members = $members;
        return $members;
    }
}