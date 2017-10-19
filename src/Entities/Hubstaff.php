<?php

namespace Noisim\Hubstaff\Entities;

use Noisim\Hubstaff\Exceptions\HubstaffActionException;

class Hubstaff extends Entity
{
    public function activities($start_time, $stop_time, $queryParams = [])
    {
        $queryParams['start_time'] = $start_time;
        $queryParams['stop_time'] = $stop_time;
        $response = $this->request()->get($this->api_url("activities", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function applicationActivities($start_time, $stop_time, $queryParams = [])
    {
        $queryParams['start_time'] = $start_time;
        $queryParams['stop_time'] = $stop_time;
        $response = $this->request()->get($this->api_url("activities/applications", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function urlActivities($start_time, $stop_time, $queryParams = [])
    {
        $queryParams['start_time'] = $start_time;
        $queryParams['stop_time'] = $stop_time;
        $response = $this->request()->get($this->api_url("activities/urls", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function screenshots($start_time, $stop_time, $queryParams = [])
    {
        $queryParams['start_time'] = $start_time;
        $queryParams['stop_time'] = $stop_time;
        $response = $this->request()->get($this->api_url("screenshots", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function notes($start_time, $stop_time, $queryParams = [])
    {
        $queryParams['start_time'] = $start_time;
        $queryParams['stop_time'] = $stop_time;
        $response = $this->request()->get($this->api_url("notes", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function note($id)
    {
        $response = $this->request()->get($this->api_url("notes/$id"))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function tasks($queryParams = [])
    {
        $response = $this->request()->get($this->api_url("tasks", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function task($id)
    {
        $response = $this->request()->get($this->api_url("tasks/$id"))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function weekly_team_report($queryParams = [])
    {
        $response = $this->request()->get($this->api_url("weekly/team", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function weekly_my_report($queryParams = [])
    {
        $response = $this->request()->get($this->api_url("weekly/my", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function custom_by_date_team($start_date, $end_date, $queryParams = [])
    {
        $queryParams['start_date'] = $start_date;
        $queryParams['end_date'] = $end_date;
        $response = $this->request()->get($this->api_url("custom/by_date/team", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function custom_by_member_team($start_date, $end_date, $queryParams = [])
    {
        $queryParams['start_date'] = $start_date;
        $queryParams['end_date'] = $end_date;
        $response = $this->request()->get($this->api_url("custom/by_member/team", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function custom_by_project_team($start_date, $end_date, $queryParams = [])
    {
        $queryParams['start_date'] = $start_date;
        $queryParams['end_date'] = $end_date;
        $response = $this->request()->get($this->api_url("custom/by_project/team", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function custom_by_date_my($start_date, $end_date, $queryParams = [])
    {
        $queryParams['start_date'] = $start_date;
        $queryParams['end_date'] = $end_date;
        $response = $this->request()->get($this->api_url("custom/by_date/my", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function custom_by_member_my($start_date, $end_date, $queryParams = [])
    {
        $queryParams['start_date'] = $start_date;
        $queryParams['end_date'] = $end_date;
        $response = $this->request()->get($this->api_url("custom/by_member/my", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function custom_by_project_my($start_date, $end_date, $queryParams = [])
    {
        $queryParams['start_date'] = $start_date;
        $queryParams['end_date'] = $end_date;
        $response = $this->request()->get($this->api_url("custom/by_project/my", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function time_edit_logs($start_time, $stop_time, $queryParams = [])
    {
        $queryParams['start_time'] = $start_time;
        $queryParams['stop_time'] = $stop_time;
        $response = $this->request()->get($this->api_url("time_edit_logs", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }

    public function team_payments($start_time, $stop_time, $queryParams = [])
    {
        $queryParams['start_time'] = $start_time;
        $queryParams['stop_time'] = $stop_time;
        $response = $this->request()->get($this->api_url("team_payments", $queryParams))->send();
        return $this->handle_response($response, new HubstaffActionException());
    }
}