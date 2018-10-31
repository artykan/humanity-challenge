<?php

namespace Http\Controllers;

use Http\Requests\StoreUserRequest;
use Http\Requests\UpdateUserRequest;
use Http\Services\Auth\CurrentUser;
use Models\UserRequest;

/**
 * Class UserRequestsController
 */
class UserRequestsController extends Controller
{
    /**
     * @return string
     */
    public function index()
    {
        $currentUser = CurrentUser::getInstance();

        $userRequest = new UserRequest;

        if ($currentUser::$is_admin) {
            $userRequests = $userRequest->all();
        } else {
            $userRequests = $userRequest->allByUserId($currentUser::$id);
        }

        return json_encode($userRequests);
    }

    /**
     * @param StoreUserRequest $request
     * @return string
     */
    public function store(StoreUserRequest $request)
    {
        $currentUser = CurrentUser::getInstance();

        $userRequest = new UserRequest;
        $userRequest->user_id = $currentUser::$id;
        $userRequest->date_start = $request->data['date_start'];
        $userRequest->date_end = $request->data['date_end'];
        $requestId = $userRequest->save();

        return json_encode($requestId);
    }

    /**
     * @param int $id
     * @param UpdateUserRequest $request
     * @return string
     * @throws \Exception
     */
    public function update(int $id, UpdateUserRequest $request)
    {
        $currentUser = CurrentUser::getInstance();

        $userRequest = new UserRequest;
        $userRequest = $userRequest->getById($id);
        if ($userRequest->user_id != $currentUser::$id) {
            throw new \Exception('Permission denied');
        }

        $userRequest->date_start = $request->data['date_start'];
        $userRequest->date_end = $request->data['date_end'];
        $userRequest->status = 'pending';
        $userRequest = $userRequest->save();

        return json_encode($userRequest);
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function destroy($id)
    {
        $currentUser = CurrentUser::getInstance();

        $userRequest = new UserRequest;
        $userRequest = $userRequest->getById($id);
        if ($userRequest->user_id != $currentUser::$id) {
            throw new \Exception('Permission denied');
        }
        $userRequest->delete();

        return json_encode(true);
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function approve($id)
    {
        $currentUser = CurrentUser::getInstance();

        $userRequest = new UserRequest;
        $userRequest = $userRequest->getById($id);
        if (!$currentUser::$is_admin) {
            throw new \Exception('Permission denied');
        }
        $userRequest->status = 'approved';
        $userRequest = $userRequest->save();

        return json_encode($userRequest);
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function reject($id)
    {
        $currentUser = CurrentUser::getInstance();

        $userRequest = new UserRequest;
        $userRequest = $userRequest->getById($id);
        if (!$currentUser::$is_admin) {
            throw new \Exception('Permission denied');
        }
        $userRequest->status = 'rejected';
        $userRequest = $userRequest->save();

        return json_encode($userRequest);
    }

    /**
     * @param string $year
     * @return string
     */
    public function remainder($year = '')
    {
        $year = empty($year) ? date('Y') : $year;

        $currentUser = CurrentUser::getInstance();

        $userRequest = new UserRequest;
        $remainder = $userRequest->remainder($currentUser::$id, $year);

        return json_encode($remainder);
    }
}
