<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $log = LogAktivitas::with('user')
            ->latest()
            ->get();

        return view('admin.log.logaktivitas', compact('log'));
    }
}
