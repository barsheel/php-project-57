<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

            <!-- Заголовок + кнопка -->
            <div class="flex justify-between items-start mb-4">
                <span class="font-bold text-gray-800 text-xl dark:text-white">Просмотр задачи: {{ $task->name }}</span>
                <a href="{{ route('tasks.edit', $task) }}"
                   class="inline-flex items-дуае px-3 py-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    &#9881;
                </a>
            </div>

            <!-- Поля задачи -->
            <div class="space-y-2">
                <p><span class="font-bold">Имя:</span> <span class="font-normal">{{ $task->name }}</span></p>
                <p><span class="font-bold">Статус:</span> <span class="font-normal">{{ $task->status->name }}</span></p>
                <p><span class="font-bold">Описание:</span> <span class="font-normal">{{ $task->description }}</span></p>
                <p class="font-bold">Метки:</p>
                <div class="flex flex-wrap gap-2 mt-1">
                    @foreach($task->labels as $label)
                        <div x-data="{ show: true }" x-show="show"
                             class="text-xs inline-flex items-center font-bold uppercase px-3 py-1 bg-blue-200 text-blue-700 rounded-full cursor-pointer"
                             @click="show = false">
                            {{ $label->name }}
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
