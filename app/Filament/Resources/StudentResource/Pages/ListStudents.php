<?php

namespace App\Filament\Resources\StudentResource\Pages;

use Filament\Actions;
use App\Models\Student;
use Illuminate\View\View;
use App\Imports\ImportStudents;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\StudentResource;
use Filament\Actions\Imports\Models\Import;
use Filament\Actions\CreateAction;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeader(): ?View
    {
        $data = Actions\CreateAction::make();
        return view('filament.custom.upload-file', compact('data'));
    }

    public $file = '';
    
    public function save()
    {
        // Student::create([
        //     'nis' => '221',
        //     'name' => 'rio',
        //     'gender' => 'male'
        // ]);

        if ($this->file != '') { 
            dd(Excel::import(new ImportStudents, $this->file));
        } 
        
    }

}