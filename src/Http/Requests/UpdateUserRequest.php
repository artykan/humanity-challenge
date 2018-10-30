<?php

namespace Http\Requests;

class UpdateUserRequest extends UserRequest
{
    public function validate()
    {
        return parent::validate();
    }
}