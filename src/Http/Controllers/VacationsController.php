<?php

namespace Http\Controllers;

class VacationsController
{
    public function index()
    {
        var_dump('index');
        die;
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
