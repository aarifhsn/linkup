<!-- Newsfeed -->
<section id="newsfeed" class="space-y-6">
    <!-- Barta Card -->
    @foreach ($posts as $post)
        <article class="bg-sky-50 border-2 border-sky-200 rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6">
            <!-- Barta Card Top -->
            <header>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <!-- User Info -->
                        <div class="text-sky-900 flex flex-col min-w-0 flex-1">
                            <a wire:navigate href="{{route('profile', $post->user->username)}}"
                                class="hover:underline font-semibold line-clamp-1">
                                {{ $post->user->first_name }} {{ $post->user->last_name }}
                            </a>

                            <a wire:navigate href="{{route('profile', $post->user->username)}}"
                                class="hover:underline text-sm text-gray-500 line-clamp-1">
                                {{'@' . $post->user->username }}
                            </a>
                        </div>
                        <!-- /User Info -->
                    </div>
                    @if (Auth::check() && Auth::user()->id == $post->user->id)
                        <!-- Card Action Dropdown -->
                        @include('components.card-action', ['post' => $post])
                        <!-- /Card Action Dropdown -->
                    @endif
                </div>
            </header>

            <!-- Content -->
            <div class="py-4 text-gray-700 font-normal">
                @if($post->image)
                    <a wire:navigate href="{{route('post.show', ['username' => $post->user->username, 'id' => $post->id])}}">
                        <img src="{{ asset('storage/' . $post->image) }}"
                            class="min-h-auto w-full rounded-lg object-cover max-h-64 md:max-h-72 mb-3" alt="" />
                    </a>
                @endif

                <div x-data="{ expanded: false }">
                    <p>
                        <!-- Display only the first 255 characters if not expanded -->
                        <span x-show="!expanded">{!! nl2br(e(substr($post->content, 0, 255))) !!}</span>

                        <!-- Display full content if expanded -->
                        <span x-show="expanded">{!! nl2br(e($post->content)) !!}</span>
                    </p>

                    <!-- Toggle button -->
                    @if (strlen($post->content) > 255)
                        <button @click="expanded = !expanded" class="text-sky-600 hover:underline mt-2">
                            <span x-show="!expanded">Show More</span>
                            <span x-show="expanded">Show Less</span>
                        </button>
                    @endif
                </div>
                <p class="text-sky-950">

                </p>
            </div>

            @include ('components.card-comment', ['post' => $post])
        </article>
    @endforeach
    <!-- /Barta Card -->

    <!-- check if there are more posts -->
    @if ($posts->hasMorePages())
        <div class="text-right">
            <button wire:click="loadMore"
                class=" text-white  bg-sky-600 hover:bg-sky-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none ">Show
                More</button>
        </div>
    @endif
</section>
<!-- /Newsfeed -->