<?php

namespace Http\Requests;

use Helpers\DateHelper;
use Http\Services\Request\Request;

class UserRequest extends Request
{
    public function validate()
    {
        if (empty($this->data['date_start'])) {
            throw new \Exception('date_start is required');
        }

        if (empty($this->data['date_end'])) {
            throw new \Exception('date_end is required');
        }

        if (!empty($this->data['date_start'])) {
            $isDateStartCorrect = DateHelper::isCorrectDate($this->data['date_start']);
            if ($isDateStartCorrect === false) {
                throw new \Exception('date_start is not a correct date [YYYY-MM-DD]');
            }
        }

        if (!empty($this->data['date_end'])) {
            $isDateEndCorrect = DateHelper::isCorrectDate($this->data['date_end']);
            if ($isDateEndCorrect === false) {
                throw new \Exception('date_end is not a correct date [YYYY-MM-DD]');
            }
        }

        if (!empty($this->data['date_start']) && !empty($this->data['date_end'])) {
            $isEndGreaterThenStart = DateHelper::isEndGreatedThanStart($this->data['date_start'], $this->data['date_end']);
            if ($isEndGreaterThenStart === false) {
                throw new \Exception('date_start greater than date_end');
            }
        }

        return parent::validate();
    }
}