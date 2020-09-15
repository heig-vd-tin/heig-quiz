<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;

class ActivityController extends Controller
{
    function index() {
        return Activity::where('class.teacher_id', User::id());
    }

    function getActivity() {
        return Activity::with(['quiz','classroom'])->find(1);
    }
}
