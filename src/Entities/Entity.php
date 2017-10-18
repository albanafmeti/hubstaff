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

    protected function api_url($path)
    {
        return rtrim($this->api_url, "/") . "/" . ltrim($path, "/");
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
        $session = new Session();
        if ($session->get('App-Token') && $session->get('Auth-Token')) {
            $this->add_request_headers([
                'App-Token' => $session->get('App-Token'),
                'Auth-Token' => $session->get('Auth-Token'),
            ]);
            return true;
        }

        $this->add_request_headers([
            'App-Token' => config("hubstaff.app_token")
        ]);

        $response = $this->request()->post($this->api_url("auth"))
            ->body([
                'email' => config("hubstaff.admin_email"),
                'password' => config("hubstaff.admin_password")
            ])->send();

        $body = $this->handle_response($response, new UserActionException());
        $this->add_request_headers([
            'App-Token' => config("hubstaff.app_token"),
            'Auth-Token' => $body->auth_token
        ]);
        $session->set('Auth-Token', $body->auth_token);
        return true;
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
        if (count($fields) == 1) {
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
    }
}