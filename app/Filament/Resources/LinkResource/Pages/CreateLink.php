<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use App\Services\LinkService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLink extends CreateRecord
{
    protected static string $resource = LinkResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(LinkService::class)->create(
            auth()->user(),
            $data['original_url'],
        );
    }
}
