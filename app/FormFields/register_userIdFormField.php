<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class register_userIdFormField extends AbstractHandler
{
    protected $codename = 'register_userId';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('vendor.voyager.formfields.register-userId', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}