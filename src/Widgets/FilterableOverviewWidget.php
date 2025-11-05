<?php

namespace Hans\FilterableOverviewWidget\Widgets;

use Closure;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Schemas\Schema;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

/**
 * @property Schema $form
 */
abstract class FilterableOverviewWidget extends BaseWidget implements HasForms
{
    use InteractsWithActions;
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected string $view = 'filterableOverviewWidget::filterable-overview';

    protected ?string $pollingInterval = null;

    public ?int $item_id = null;

    protected function getColumns(): int
    {
        return 4;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(
                [
                    Select::make('item_id')
                        ->label(
                            function (Select $component) {
                                if (filled($this->getItemLabel())) {
                                    return $this->getItemLabel();
                                }

                                $component->hiddenLabel();
                            }
                        )
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
