<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use App\Models\Classroom;
use App\Models\Periode;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use PhpParser\Builder\Function_;
use Filament\Forms\Components\Actions\Action;

class ClassroomRelationManager extends RelationManager
{
    protected static string $relationship = 'classroom';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('classrooms_id')
                    ->label('Select Class')
                    ->options(Classroom::all()->pluck('name', 'id'))
                    ->searchable()
                    ->relationship(name: 'classroom', titleAttribute: 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    Hidden::make('slug'),
                    ])
                     ->createOptionAction(
                        fn (Action $action) => 
                            $action->modalWidth('3xl') 
                                -> modalHeading('Add Class')
                                -> modalButton('Add Class')
                     ),


                Select::make('periode_id')
                    ->label('Select Periode')
                    ->options(Periode::all()->pluck('name', 'id'))
                    ->searchable()
                    ->relationship(name: 'periode', titleAttribute: 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                    ])
                     ->createOptionAction(
                        fn (Action $action) => 
                            $action->modalWidth('3xl') 
                                -> modalHeading('Add Periode')
                                -> modalButton('Add Periode')
                     )
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('classroom.name'),
                Tables\Columns\TextColumn::make('periode.name'),
                ToggleColumn::make('is_open'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
