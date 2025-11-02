<?php

namespace Hans\FilterableOverviewWidget\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class FilterableOverviewWidgetCommand extends Command
{
    public $signature = '
    make:filterable-overview-widget
    {name : Name of the widget}
    {--p|panel=default : Select a panel}
    ';

    public $description = 'Create a Filterable stats overview widget class';

    public function handle(): int
    {
        $name = $this->argument('name');
        $panel = $this->option('panel');

        $path = app_path('Filament/Widgets');
        $namespace = 'App\\Filament\\Widgets';
        if ($panel !== 'default') {
            $panel = ucfirst($panel);
            $path = app_path("Filament/{$panel}/Widgets");
            $namespace = 'App\\Filament\\' . ucfirst($panel) . '\\Widgets';
        }
        $path .= '/' . ucfirst($name) . '.php';

        $filesystem = app(Filesystem::class);

        $stub = $filesystem->get(__DIR__ . '/../../stubs/FilterableStatsOverviewWidget.stub');
        $stub = str_replace('{{NAMESPACE}}', $namespace, $stub);
        $stub = str_replace('{{NAME}}', ucfirst($name), $stub);

        $filesystem->ensureDirectoryExists(
            pathinfo($path, PATHINFO_DIRNAME),
        );
        $filesystem->put($path, $stub);

        $this->info('Widget class created successfully.');

        return self::SUCCESS;
    }
}
