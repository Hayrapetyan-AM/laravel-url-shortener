<?php

namespace App\Http\Controllers;

use App\Exceptions\LinkNotFoundException;
use App\Services\RedirectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

final class RedirectController extends Controller
{
    public function __construct(
        private readonly RedirectService $redirectService,
    ) {}

    public function __invoke(string $shortCode): RedirectResponse
    {
        try {
            $url = $this->redirectService->resolve($shortCode, request()->ip() ?? '0.0.0.0');

            return redirect()->away($url);
        } catch (LinkNotFoundException $exception) {
            abort(404);
        } catch (\Throwable $exception) {
            Log::error('Redirect failed.', [
                'short_code' => $shortCode,
                'exception' => $exception,
            ]);

            abort(500);
        }
    }
}
