<div class="space-y-4 ">
    @foreach($posts as $post)
        <div class="bg-white p-4 rounded shadow mb-4">
            <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
            <p class="text-gray-700">{{ $post->content }}</p>

            <!-- Likes del post -->
            <div class="flex items-center mt-2">
                <button wire:click="toggleLike({{ $post->id }})" class="mr-2 bg-gray-200 hover:bg-gray-300 rounded-full p-2">
                    @if($post->likes()->where('user_id', Auth::id())->exists())
                        Unlike
                    @else
                        Like
                    @endif
                </button>
                <span>{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</span>
            </div>

            <!-- Mostrar comentarios -->
            <h3 class="mt-4 text-lg font-semibold">Comentarios:</h3>
            @foreach($post->comments as $comment)
                <div class="mt-2">
                    <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}</p>

                    <!-- Likes del comentario -->
                    <div class="flex items-center mt-1">
                        <button wire:click="toggleCommentLike({{ $comment->id }})" class="mr-2 bg-gray-200 hover:bg-gray-300 rounded-full p-1">
                            @if($comment->likes()->where('user_id', Auth::id())->exists())
                                Unlike
                            @else
                                Like
                            @endif
                        </button>
                        <span>{{ $comment->likes->count() }} {{ Str::plural('like', $comment->likes->count()) }}</span>
                    </div>
                </div>
            @endforeach

            <!-- Botón para mostrar/ocultar textarea -->
            <button wire:click="toggleShowComment" class="mt-4 bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                {{ $showComment ? 'Ocultar Comentario' : 'Agregar Comentario' }}
            </button>

            <!-- Formulario para agregar comentarios -->
            @if($showComment)
                <form wire:submit.prevent="addComment({{ $post->id }})" class="mt-4">
                    <textarea wire:model="newComment" placeholder="Escribe tu comentario" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"></textarea>
                    <button type="submit" class="mt-2 inline-block bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">Enviar Comentario</button>
                </form>
            @endif
        </div>
    @endforeach
</div>
