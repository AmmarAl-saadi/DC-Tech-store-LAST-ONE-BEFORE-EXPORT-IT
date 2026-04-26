<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class PdfServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Mpdf::class, function () {

            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            return new Mpdf([
                'tempDir' => public_path('/uploads'),

                'fontDir' => array_merge($fontDirs, [
                    resource_path('fonts'),
                ]),

                'fontdata' => $fontData + [
                        'notosans' => [
                            'R'  => 'NotoSans-Regular.ttf',
                            'B'  => 'NotoSans-Bold.ttf',
                            'I'  => 'NotoSans-Italic.ttf',
                            'BI' => 'NotoSans-BoldItalic.ttf',
                        ]
                    ],

                'default_font' => 'notosans'
            ]);
        });
    }
}
