<?php

namespace App\Service;

class CreateFolder
{
    public function __construct()
    {
        mkdir('mytestfolder',0777,true);
    }
}