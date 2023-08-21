<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, ResponseHelper, FileHelper;
}
