<x-guest-layout>
    <div class="py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">
                <div class="px-8 py-10">
                    <div class="text-center">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">URL Shortener</h1>
                        <p class="mx-auto mt-4 max-w-2xl text-base text-gray-600">
                            Paste a long URL and get a short link instantly. Register to manage links and view click statistics.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('links.store') }}" class="mx-auto mt-8 max-w-2xl space-y-4">
                        @csrf

                        <div>
                            <label for="original_url" class="sr-only">Original URL</label>
                            <input
                                id="original_url"
                                name="original_url"
                                type="url"
                                required
                                placeholder="https://example.com/page"
                                value="{{ old('original_url') }}"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <x-input-error :messages="$errors->get('original_url')" class="mt-2" />
                        </div>

                        <button type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-500">
                            Shorten URL
                        </button>
                    </form>

                    @if (session('createdShortUrl'))
                        <div class="mx-auto mt-6 max-w-2xl rounded-lg border border-green-200 bg-green-50 p-4">
                            <p class="text-sm font-medium text-green-800">Your short link is ready</p>
                            <div class="mt-2 flex flex-col gap-2 sm:flex-row sm:items-center">
                                <input
                                    type="text"
                                    readonly
                                    value="{{ session('createdShortUrl') }}"
                                    class="block w-full rounded-md border-gray-300 bg-white text-sm"
                                >
                                <a href="{{ session('createdShortUrl') }}"
                                   target="_blank"
                                   class="inline-flex shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                    Open
                                </a>
                            </div>
                            @guest
                                <p class="mt-3 text-sm text-green-700">
                                    <a href="{{ route('register') }}" class="font-medium underline">Create an account</a>
                                    to track clicks and manage this link later.
                                </p>
                            @endguest
                        </div>
                    @endif

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                        @auth
                            <a href="{{ url('/admin') }}"
                               class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-500">
                                Open dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                                Sign in
                            </a>
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                                Create account
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="grid gap-px bg-gray-200 sm:grid-cols-3">
                    <div class="bg-gray-50 px-6 py-8 text-center">
                        <p class="text-sm font-semibold text-gray-900">Short links</p>
                        <p class="mt-2 text-sm text-gray-600">Anyone can shorten a URL without signing in.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-8 text-center">
                        <p class="text-sm font-semibold text-gray-900">Click tracking</p>
                        <p class="mt-2 text-sm text-gray-600">Every visit records IP address and time.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-8 text-center">
                        <p class="text-sm font-semibold text-gray-900">Personal cabinet</p>
                        <p class="mt-2 text-sm text-gray-600">Registered users manage links and stats in the dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
