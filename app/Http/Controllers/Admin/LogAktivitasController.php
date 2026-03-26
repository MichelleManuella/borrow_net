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
            ->paginate(10);

        return view('admin.log.logaktivitas', compact('log'));
    }
}
