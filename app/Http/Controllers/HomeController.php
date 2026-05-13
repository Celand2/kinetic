<?php

namespace App\Http\Controllers;

use App\Models\TradingCycle;

class HomeController extends Controller
{
    public function index()
    {
        $cycles = TradingCycle::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        // Scan toutes les images dans public/images/slides/
        $slidesDir = public_path('images/slides');
        $slides    = [];
        if (is_dir($slidesDir)) {
            $files = glob($slidesDir . '/*.{jpg,jpeg,png,webp,gif,JPG,JPEG,PNG,WEBP}', GLOB_BRACE);
            if ($files) {
                sort($files);
                foreach ($files as $file) {
                    $slides[] = 'images/slides/' . basename($file);
                }
            }
        }
        // Fallback si aucune image : placeholder sombre
        if (empty($slides)) {
            $slides = ['images/slides/placeholder.jpg'];
        }

        return view('index', compact('cycles', 'slides'));
    }
}
