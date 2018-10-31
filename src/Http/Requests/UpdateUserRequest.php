<?php

namespace Http\Requests;

/**
 * Class UpdateUserRequest
 */
class UpdateUserRequest extends UserRequest
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