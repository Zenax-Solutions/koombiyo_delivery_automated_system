<?php
// app/Filament/Resources/WaybillSequenceResource/Forms/WaybillSequenceForm.php

use Filament\Forms;

Forms\Components\TextInput::make('start_number')
    ->required()
    ->numeric();
Forms\Components\TextInput::make('end_number')
    ->required()
    ->numeric();
Forms\Components\Hidden::make('current_number')
    ->default(fn ($get) => $get('start_number'));
Forms\Components\Checkbox::make('active')
    ->default(false);
