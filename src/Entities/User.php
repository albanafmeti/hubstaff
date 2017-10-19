<?php

namespace Noisim\Hubstaff\Entities;

use Noisim\Hubstaff\Exceptions\UserActionException;

class User extends Entity
{
    public $id;
    public $email;
    public $password;
    public $name;
    public $last_activity;

    public $projects = [];
    public $organizations = [];

    function __construct($userEmail = null, $password = null, $name = null)
    {
        parent::__construct();
        $this->create($userEmail, $password, $name);
    }

    public function create($userEmail = null, $password = null, $name = null)
    {
        if (is_array($userEmail)) {
            foreach ($userEmail as $field => $value) {
                $this->{$field} = $value;
            }
        } else {
            $this->email = ($userEmail) ? $userEmail : $this->email;
            $this->password = ($password) ? $password : $this->password;
            $this->name = ($name) ? $name : $this->name;
        }
        return $this;
    }

    public function login($auth_headers = false)
    {
        $response = $this->request()->post($this->api_url("auth"))
            ->body(['email' => $this->email, 'password' => $this->password])
            ->send();

        $user = $this->handle_response($response, new UserActionException(), ["user"]);
        $this->id = $user->id;
        $this->name = $user->name;

        if ($auth_headers) {
            $this->add_request_headers([
                'Auth-Token' => $user->auth_token
            ]);
        }
        return $this;
    }

    public function all($queryParams = [])
    {
        $response = $this->request()->get($this->api_url("users", $queryParams))->send();
        return $this->handle_response($response, new UserActionException(), ['users']);
    }

    public function get($id = null)
    {
        $id = ($id) ? $id : $this->id;

        if (!$id) {
            throw new UserActionException("User ID not specified.");
        }

        $response = $this->request()->get($this->api_url("users/$id"))->send();
        $user = $this->handle_response($response, new UserActionException(), ['user']);
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->last_activity = $user->last_activity;
        return $this;
    }

    public function organizations($id = null, $queryParams = [])
    {
        $id = ($id) ? $id : $this->id;

        if (!$id) {
            throw new UserActionException("User ID not specified.");
        }

        $response = $this->request()->get($this->api_url("users/$id/organizations", $queryParams))->send();
        $organizations = $this->handle_response($response, new UserActionException(), ['organizations']);
        $this->organizations = $organizations;
        return $organizations;
    }

    public function projects($id = null, $queryParams = [])
    {
        $id = ($id) ? $id : $this->id;

        if (!$id) {
            throw new UserActionException("User ID not specified.");
        }

        $response = $this->request()->get($this->api_url("users/$id/projects", $queryParams))->send();
        $projects = $this->handle_response($response, new UserActionException(), ['projects']);
        $this->projects = $projects;
        return $projects;
    }
}