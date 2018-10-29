<?php

namespace Http\Controllers;

use Http\Services\Auth\CurrentUser;
use Models\Request;
use Http\Services\Request\Request as HttpRequest;

class RequestsController extends Controller
{
    public function index()
    {
        $currentUser = CurrentUser::getInstance();
        $request = new Request;
        $requests = $request->allByUserId($currentUser::$id);
        return json_encode($requests);
    }

    public function store()
    {
        $httpRequest = HttpRequest::getInstance();
        $currentUser = CurrentUser::getInstance();
        $request = new Request;
        $request->user_id = $currentUser::$id;
        $request->date_start = $httpRequest->data['date_start'];
        $request->date_end = $httpRequest->data['date_end'];
        $requestId = $request->save();
        return json_encode($requestId);
    }

    public function update(int $id)
    {
        $httpRequest = HttpRequest::getInstance();
        $currentUser = CurrentUser::getInstance();
        $request = new Request;
        $request = $request->getById($id);
        if ($request->user_id != $currentUser::$id) {
            throw new \Exception('Permission denied');
        }
        if (!empty($httpRequest->data['date_start'])) {
            $request->date_start = $httpRequest->data['date_start'];
        }
        if (!empty($httpRequest->data['date_end'])) {
            $request->date_end = $httpRequest->data['date_end'];
        }
        $request->status = 'pending';
        $request = $request->save();
        return json_encode($request);
    }

    public function destroy($id)
    {
        $currentUser = CurrentUser::getInstance();
        $request = new Request;
        $request = $request->getById($id);
        if ($request->user_id != $currentUser::$id) {
            throw new \Exception('Permission denied');
        }
        $request->delete();
        return json_encode(true);
    }

    public function approve($id)
    {
        $currentUser = CurrentUser::getInstance();
        $request = new Request;
        $request = $request->getById($id);
        if (!$currentUser::$is_admin) {
            throw new \Exception('Permission denied');
        }
        $request->status = 'approved';
        $request = $request->save();
        return json_encode($request);
    }

    public function reject($id)
    {
        $currentUser = CurrentUser::getInstance();
        $request = new Request;
        $request = $request->getById($id);
        if (!$currentUser::$is_admin) {
            throw new \Exception('Permission denied');
        }
        $request->status = 'rejected';
        $request = $request->save();
        return json_encode($request);
    }
}
