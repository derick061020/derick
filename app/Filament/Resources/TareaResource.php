<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TareaResource\Pages;
use App\Filament\Resources\TareaResource\RelationManagers;
use App\Models\Tarea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TareaResource extends Resource
{
    protected static ?string $model = Tarea::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\Textarea::make('descripcion')
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('completada')
                    ->required(),
                Forms\Components\DatePicker::make('fecha_limite')
                    ->nullable()
                    ->displayFormat('d/m/Y'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->searchable(),
                Tables\Columns\IconColumn::make('completada')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('completar')
                    ->action(function (Tarea $record) {
                        $record->completada = true;
                        $record->save();
                    })
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Marcar como completada')
                    ->modalDescription('¿Estás seguro de que quieres marcar esta tarea como completada?')
                    ->modalButton('Marcar como completada')
                    ->visible(function (Tarea $record) {
                        return !$record->completada;
                    }),

                Tables\Actions\Action::make('desmarcar')
                    ->action(function (Tarea $record) {
                        $record->completada = false;
                        $record->save();
                    })
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Desmarcar como completada')
                    ->modalDescription('¿Estás seguro de que quieres desmarcar esta tarea como completada?')
                    ->modalButton('Desmarcar')
                    ->visible(function (Tarea $record) {
                        return $record->completada;
                    }),
            ])
            ->bulkActions([])
            ->defaultSort('completada', 'asc');
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
            'index' => Pages\ListTareas::route('/'),
            'create' => Pages\CreateTarea::route('/create'),
            'edit' => Pages\EditTarea::route('/{record}/edit'),
        ];
    }
}
