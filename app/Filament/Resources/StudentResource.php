<?php

namespace App\Filament\Resources;

use stdClass;
use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nis')
                    ->label('NIS'),
                TextInput::make('name')
                    ->label('Student Name'),
                Select::make('gender')
                    ->options([
                        "Male" => "Male",
                        "Female" => "Female"
                    ]),
                DatePicker::make('birthday')
                    ->label("Birthday"),
                    Select::make("gender")
                        ->options([
                            "islam" => "Islam",
                            "Katolik" => "Katolik",
                            "Protestan" => "Protestan",
                            "Hindu" => "Hindu",
                            "Buddha" => "Buddha",
                            "Khonghucu" => "Khonghucu",
                        ]),
                TextInput::make('contact'),
                FileUpload::make('profile')
                        ->directory("student")
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->state(
                static function (HasTable $livewire, stdClass $rowLoop): string {
                    return (string) (
                        $rowLoop->iteration +
                        ($livewire->getTableRecordsPerPage() * (
                            $livewire->getTablePage() - 1
                        ))
                    );
                }
            ),
                TextColumn::make('nis')
                    ->label('NIS'),
                TextColumn::make('name')
                    ->label('Student Name'),
                TextColumn::make('gender'),
                TextColumn::make('birthday')
                    ->label("Birthday"),
                TextColumn::make("gender"),
                TextColumn::make('contact'),
                ImageColumn::make('profile')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

}
