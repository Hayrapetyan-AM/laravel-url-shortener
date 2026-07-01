<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Services\LinkService;
use Illuminate\Http\RedirectResponse;

final class LinkController extends Controller
{
    public function __construct(
        private readonly LinkService $linkService,
    ) {}

    public function store(StoreLinkRequest $request): RedirectResponse
    {
        $link = $this->linkService->create(
            $request->user(),
            $request->validated('original_url'),
        );

        return back()->with('createdShortUrl', $link->shortUrl());
    }
}
