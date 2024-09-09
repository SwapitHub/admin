<?php

use Illuminate\Support\Facades\DB;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

if (!function_exists('sendMail')) {
    function sendMail($to_email,$template,$model)
    {
      //send mail function here
    }
}

