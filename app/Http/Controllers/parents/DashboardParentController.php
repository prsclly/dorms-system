<?php

namespace App\Http\Controllers\parents;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Parents;


class DashboardParentController extends Controller
{
    public function index()
    {
       /** @var Parents $parent */
$parent = Auth::guard('parent')->user(); // ini tetap
$students = $parent->students()->with('pointLogs')->get(); // sekarang sudah akan dikenali


        // Kirim data ke view dashboard
        return view('content.parents.dashboard_parents', compact('parent', 'students'));
    }

    public function pointLogs()
    {
       /** @var Parents $parent */
    $parent = Auth::guard('parent')->user();

    $students = $parent->students()->with(['pointLogs' => function ($query) {
        $query->latest();
    }])->get();

    return view('content.parents.point_logs', compact('parent', 'students'));
    }

}
