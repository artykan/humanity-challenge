<?php

namespace Http\Requests;

class StoreUserRequest extends UserRequest
{
    public function validate()
    {
        return parent::validate();
    }
}