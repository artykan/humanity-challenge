<?php

namespace Http\Controllers;

use Models\Vacation;

class RequestsController
{
    public function index()
    {
        $vacation = new Vacation;
        $vacations = $vacation->all();
        return json_encode($vacations);
    }

    public function store()
    {
        var_dump('store');
        die;
    }

    public function update($id)
    {
        var_dump('update');
        var_dump($id);
        die;
    }

    public function destroy($id)
    {
        var_dump('destroy');
        var_dump($id);
        die;
    }

    public function export($type = 'csv')
    {
        var_dump('export');
        var_dump($type);
        die;
    }
}
