<?php

use Illuminate\Filesystem\Filesystem;

use function Pest\Laravel\artisan;

afterEach(function () {
    app(Filesystem::class)->deleteDirectories(app_path('Filament'));
});

it(
    'Run make command',
    function () {
        $file = app_path('Filament/Widgets/MyFilterableStatsOverviewWidget.php');
        expect($file)->not->toBeFile();

        artisan('make:filterable-overview-widget MyFilterableStatsOverviewWidget')
            ->assertExitCode(0);

        expect($file)->toBeFile();
    }
);

it(
    'Check make command output',
    function () {
        artisan('make:filterable-overview-widget MyFilterableStatsOverviewWidget')
            ->expectsOutput('Widget class created successfully.')
            ->assertExitCode(0);
    }
);

it(
    'Run make command with a panel specified',
    function () {
        $file = app_path('Filament/Writers/Widgets/MyFilterableStatsOverviewWidget.php');
        expect($file)->not->toBeFile();

        artisan('make:filterable-overview-widget --panel writers MyFilterableStatsOverviewWidget')
            ->assertExitCode(0);

        expect($file)->toBeFile();
    }
);

it(
    'Check stub content',
    function () {
        $file = app_path('Filament/Widgets/MyFilterableStatsOverviewWidget.php');

        artisan('make:filterable-overview-widget MyFilterableStatsOverviewWidget')
            ->assertExitCode(0);

        expect($file)
            ->toEndWith('MyFilterableStatsOverviewWidget.php')
            ->and(file_get_contents($file))
            ->toBe(
                <<<'STUB'
<?php

namespace App\Filament\Widgets;

use Closure;
use Hans\FilterableOverviewWidget\Widgets\FilterableOverviewWidget as BaseWidget;

class MyFilterableStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = -1;

    protected function getSearchResultsUsing(): Closure
    {
        return function (string $search): array {
            return;
        };
    }

    protected function getOptionLabelUsing(): Closure
    {
        return function (string $value): string {
            return;
        };
    }

    protected function getStats(): array
    {
        return [];
    }
}

STUB
            );
    }
);
