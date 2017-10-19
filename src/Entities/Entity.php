<?php

namespace Noisim\Hubstaff\Entities;

use Noisim\Hubstaff\Helpers\HubstaffRequest;
use Noisim\Hubstaff\Exceptions\UserActionException;
use Symfony\Component\HttpFoundation\Session\Session;

class Entity
{
    private $api_url;
    private $request;

    function __construct()
    {
        $this->api_url = config("hubstaff.api_url") . config("hubstaff.api_root");
        $this->request = HubstaffRequest::singleton();
        $this->admin_login();
    }

    protected function api_url($path, $queryParams = [])
    {
        $path = rtrim($this->api_url, "/") . "/" . ltrim($path, "/");
        return $path . "?" . http_build_query($queryParams);
    }

    protected function add_request_headers($headers)
    {
        HubstaffRequest::add_headers($headers);
    }

    protected function request()
    {
        return $this->request;
    }

    private function admin_login()
    {
        $this->add_request_headers([
            'App-Token' => config("hubstaff.app_token")
        ]);

        if (config("hubstaff.admin_email") && config("hubstaff.admin_password")) {

            $session = new Session();
            if ($session->get('Auth-Token')) {
                $this->add_request_headers([
                    'Auth-Token' => $session->get('Auth-Token'),
                ]);
                return true;
            }

            $response = $this->request()->post($this->api_url("auth"))
                ->body([
                    'email' => config("hubstaff.admin_email"),
                    'password' => config("hubstaff.admin_password")
                ])->send();

            $user = $this->handle_response($response, new UserActionException(), ['user']);

            $this->add_request_headers([
                'App-Token' => config("hubstaff.app_token"),
                'Auth-Token' => $user->auth_token
            ]);
            $session->set('Auth-Token', $user->auth_token);
            return true;
        }
        return false;
    }

    public function handle_response($response, $exception, $fields = [])
    {
        $fields = is_string($fields) ? [$fields] : $fields;
        try {
            if ($response->code == 200) {

                return $this->data($response->body, $fields);

            } else {
                if (isset($response->body->error)) {
                    $exception->setMessage($response->body->error);
                } else {
                    $exception->setMessage("Something went wrong.");
                }
            }

        } catch (\Exception $ex) {
            $exception->setMessage("Something went wrong.");
        }
        throw $exception;
    }

    private function data($body, $fields)
    {
        if (count($fields) == 0) {
            return $body;
        } else if (count($fields) == 1) {
            return isset($body->{$fields[0]}) ? $body->{$fields[0]} : $body;
        } else if (count($fields) == 2) {
            $stepOne = isset($body->{$fields[0]}) ? $body->{$fields[0]} : $body;
            $stepTwo = isset($stepOne->{$fields[1]}) ? $stepOne->{$fields[1]} : $stepOne;
            return $stepTwo;
        } else if (count($fields) == 3) {
            $stepOne = isset($body->{$fields[0]}) ? $body->{$fields[0]} : $body;
            $stepTwo = isset($stepOne->{$fields[1]}) ? $stepOne->{$fields[1]} : $stepOne;
            $stepThree = isset($stepTwo->{$fields[2]}) ? $stepTwo->{$fields[2]} : $stepTwo;
            return $stepThree;
        }
        return $body;
    }
}