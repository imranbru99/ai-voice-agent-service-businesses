<?php

namespace App\Http\Controllers;

use App\Services\ModuleManager;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function home(ModuleManager $modules): View
    {
        return view('marketing.home', [
            'modules' => $modules->all(),
            'accent' => '#16A34A',
        ]);
    }

    public function features(ModuleManager $modules): View
    {
        return view('marketing.features', [
            'modules' => $modules->all(),
            'accent' => '#16A34A',
        ]);
    }

    public function modules(ModuleManager $modules): View
    {
        return view('marketing.modules', [
            'modules' => $modules->all(),
            'accent' => '#16A34A',
        ]);
    }

    public function pricing(): View
    {
        return view('marketing.pricing', [
            'accent' => '#16A34A',
        ]);
    }

    public function demo(): View
    {
        return view('marketing.demo', [
            'accent' => '#16A34A',
        ]);
    }
}
