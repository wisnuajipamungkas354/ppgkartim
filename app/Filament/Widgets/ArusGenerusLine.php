<?php

namespace App\Filament\Widgets;

use App\Traits\HandlesPermissionWidget;
use Filament\Widgets\ChartWidget;

class ArusGenerusLine extends ChartWidget
{
    use HandlesPermissionWidget;
    
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
