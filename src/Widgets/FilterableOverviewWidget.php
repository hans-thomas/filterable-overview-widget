<?php

namespace Hans\FilterableOverviewWidget\Widgets;

use Closure;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

/**
 * @property Form $form
 */
abstract class FilterableOverviewWidget extends BaseWidget implements HasForms
{
    use InteractsWithActions;
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static string $view = 'filamentFilterableOverviewWidget::filterable-overview';

    protected static ?string $pollingInterval = null;

    public ?int $item_id = null;

    protected function getColumns(): int
    {
        return 4;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Select::make('item_id')
                        ->label($this->getItemLabel())
                        ->lazy()
                        ->afterStateUpdated(fn ($state) => $this->processFormData())
                        ->searchable()
                        ->getSearchResultsUsing($this->getSearchResultsUsing())
                        ->getOptionLabelUsing($this->getOptionLabelUsing()),
                ]
            );
    }

    public function processFormData(): void
    {
        $this->form->validate();
        $this->resetCachedStats();
    }

    protected function resetCachedStats(): void
    {
        $this->cachedStats = null;
    }

    abstract protected function getSearchResultsUsing(): Closure;

    abstract protected function getOptionLabelUsing(): Closure;

    protected function getItemLabel(): ?string
    {
        return '';
    }
}
