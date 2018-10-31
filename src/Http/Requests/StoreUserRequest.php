<?php

namespace Http\Requests;

/**
 * Class StoreUserRequest
 */
class StoreUserRequest extends UserRequest
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function validate()
    {
        return parent::validate();
    }
}