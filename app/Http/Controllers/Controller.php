<?php
/**
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2020.
 */

namespace App\Http\Controllers;

use App\Traits\BaseResponseTraits;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, BaseResponseTraits;
}
