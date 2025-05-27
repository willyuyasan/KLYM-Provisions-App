<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Provinvoice;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProvinvoiceResource\Pages;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\ProvinvoiceResource\RelationManagers;

class ProvinvoiceResource extends Resource
{
    protected static ?string $model = Provinvoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Facturas Provisionadas';
    protected static ?string $modelLabel = 'Facturas Provisionadas';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id_invoice')
                    ->size(TextColumnSize::ExtraSmall)
                    ->copyable()
                    ->wrap()
                    //->lineClamp(2)
                    ->grow(false)
                    ->searchable(),

                TextColumn::make('country_code')
                    ->label('Codigo pais')
                    ->grow(false),
                
                TextColumn::make('product')
                    ->label('Producto')
                    ->grow(false),
                
                TextColumn::make('funder_group')
                    ->label('Segmento fondeador')
                    ->grow(false),
                
                TextColumn::make('days_rel_due')
                    ->label('Dias en mora')
                    ->grow(false)
                    ->numeric(decimalPlaces: 0),
                
                TextColumn::make('actual_debt')
                    ->label('Deuda actual')
                    ->grow(false)
                    ->numeric(decimalPlaces: 0),

                TextColumn::make('perc_provision')
                    ->label('% Provisión')
                    ->grow(false)
                    ->numeric(decimalPlaces: 6),

                TextColumn::make('provision')
                    ->label('Valor provision')
                    ->grow(false)
                    ->numeric(decimalPlaces: 2),
                
                TextColumn::make('provision_obs')
                    ->label('Observación cálculo provisión')
                    ->grow(false),
                
                TextColumn::make('issuer_name')
                    ->label('Cliente')
                    ->description(fn ($record): string => $record->issuer_tax_number)
                    ->limit(20)
                    ->grow(false)
                    ->searchable(['issuer_name','issuer_tax_number']),

                TextColumn::make('debtor_name')
                    ->label('Deudor')
                    ->description(fn ($record): string => $record->issuer_tax_number)
                    ->limit(20)
                    ->grow(false)
                    ->searchable(['debtor_name','debtor_tax_number']),

                TextColumn::make('funder_name')
                    ->label('Fondeador')
                    ->description(fn ($record): string => $record->issuer_tax_number)
                    ->limit(20)
                    ->grow(false)
                    ->searchable(['funder_name','funder_tax_number']),

            ])
            ->defaultSort('provision','desc')

            ->filters([
                //
                SelectFilter::make('country_code')
                ->label('Código pais')
                ->options(fn (): array => Provinvoice::query()->pluck('country_code','country_code')->all()),

                SelectFilter::make('product')
                ->label('Producto')
                ->options(fn (): array => Provinvoice::query()->pluck('product','product')->all()),

                SelectFilter::make('funder_group')
                ->label('Segmento fondeador')
                ->options(fn (): array => Provinvoice::query()->pluck('funder_group','funder_group')->all())
                ->default(null),
                

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProvinvoices::route('/'),
            'create' => Pages\CreateProvinvoice::route('/create'),
            'edit' => Pages\EditProvinvoice::route('/{record}/edit'),
        ];
    }

    // Registering the new widget
    public static function getWidgets(): array
    {
        return [
            ProvinvoiceResource\Widgets\ProvisionSummary::class,
        ];
    }
}
