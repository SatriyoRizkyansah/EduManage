<?php

namespace App\Filament\Resources;

use stdClass;
use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Split;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Illuminate\Database\Eloquent\Collection;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;


class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationParentItem = 'Teacher';
 
    protected static ?string $navigationGroup = 'Academic';

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
                    Select::make("religion")
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
                    ->label("Birthday")
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("gender")
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('contact'),
                ImageColumn::make('profile'),
                TextColumn::make('status')
                    ->toggleable(isToggledHiddenByDefault: true) 
                    ->formatStateUsing(fn (string $state): string => ucwords("{$state}"))
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'accept' => 'Accept',
                        'off' => 'Off',
                        'move' => 'Move',
                        'grade' => 'Grade',
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Accept')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->form([
                            Select::make('Status')
                            ->label("Status")
                            ->options(['accept' => 'Accept', 'off' => 'Off', 'move' => 'Move', 'grade' => 'Grade',])
                            ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function($record) use ($data){
                                Student::where('id', $record->id)->update(['status' => $data['Status']]);
                            });
                        }),
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
            'view' => Pages\ViewStudent::route('/{record}'),

        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Fieldset::make('Biodata')
                            ->schema([
                                Split::make([
                                    ImageEntry::make('profile')
                                        ->hiddenLabel()
                                        ->grow(false),
                                    Grid::make(2)
                                        ->schema([
                                            Group::make([
                                                TextEntry::make('nis'),
                                                TextEntry::make('name'),
                                                TextEntry::make('gender'),
                                                TextEntry::make('birthday'),

                                            ])
                                            ->inlineLabel()
                                            ->columns(1),

                                            Group::make([
                                                TextEntry::make('religion'),
                                                TextEntry::make('contact'),
                                                TextEntry::make('status')
                                                ->badge()
                                                ->color(fn (string $state): string => match ($state) {
                                                    'accept' => 'success',
                                                    'off' => 'danger',
                                                    'grade' => 'success',
                                                    'move' => 'warning',
                                                    'wait' => 'gray'
                                                }),
                                                // ViewEntry::make('QRCode')
                                                // ->view('filament.resources.students.qrcode'),
                                            ])
                                            ->inlineLabel()
                                            ->columns(1),
                                    ])

                                ])->from('lg')
                            ])->columns(1)
                    ])->columns(2)
            ]);
    }

}
