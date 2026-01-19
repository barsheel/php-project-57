<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Задачи</h1>
    </x-slot>

    <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
        <div class="grid col-span-full">
            <div class="w-full flex items-center">
                <div>
                    <form method="GET" action="{{ route('tasks.index') }}">
                        <div class="flex">
                            <select class="rounded border-gray-300" name="filter[status_id]" id="filter[status_id]">
                                <option value="">Статус</option>
                                @foreach($statuses as $statusId => $statusName)
                                    @if(request()->input('filter.status_id') === $statusId)
                                        <option selected="selected"
                                                value="{{ $statusId }}">{{ $statusName }}</option>
                                    @else
                                        <option value="{{ $statusId }}">{{ $statusName }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <select class="rounded border-gray-300" name="filter[created_by_id]"
                                    id="filter[created_by_id]">
                                <option value="">Автор</option>
                                @foreach($users as $userId => $userName)
                                    @if(request()->input('filter.created_by_id') ===  $userId)
                                        <option selected="selected" value="{{ $userId }}">{{ $userName }}</option>
                                    @else
                                        <option value="{{ $userId }}">{{ $userName }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <select class="rounded border-gray-300" name="filter[assigned_to_id]"
                                    id="filter[assigned_to_id]">
                                <option value="">Исполнитель</option>
                                @foreach($users as $userId => $userName)
                                    @if(request()->input('filter.assigned_to_id') === (string) $userId)
                                        <option selected="selected" value="{{ $userId }}">{{ $userName }}</option>
                                    @else
                                        <option value="{{ $userId }}">{{ $userName }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                                    type="submit">Применить</button>

                        </div>
                    </form>
                </div>

                <div class="ml-auto">
                    @auth
                        <a href="{{ route('tasks.create') }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">Создать задачу</a>
                    @endauth
                </div>
            </div>

            <table class="mt-4">
                <thead class="border-b-2 border-solid border-black text-left">
                <tr>
                    <th>ID</th>
                    <th>Статус</th>
                    <th>Имя</th>
                    <th>Автор</th>
                    <th>Исполнитель</th>
                    <th>Дата создания</th>
                    @auth()
                        <th>Действия</th>
                    @endauth
                </tr>
                </thead>
                <tbody>
                @if(!empty($tasks))
                    @foreach($tasks as $task)
                        <tr class="border-b border-dashed text-left">
                            <td>{{ $task->id }}</td>
                            <td>{{ $task->status->name }}</td>
                            <td>
                                <a class="text-blue-600 hover:text-blue-900"
                                   href="{{ route('tasks.show', $task) }}">
                                    {{ $task->name }}
                                </a>
                            </td>
                            <td>{{ $task->creator->name }}</td>
                            <td>{{ $task->assigned->name ?? '' }}</td>
                            <td>{{ Carbon\Carbon::createFromDate($task->created_at)->format('d.m.Y') }}</td>
                            <td>
                                @auth
                                    @can('delete', $task)
                                        <a rel="nofollow" data-confirm="Вы уверены?" data-method="delete"
                                           href="{{ route('tasks.destroy', $task) }}"
                                           class="text-red-600 hover:text-red-900">Удалить</a>
                                    @endcan
                                    <a href="{{ route('tasks.edit', $task) }}"
                                       class="text-blue-600 hover:text-blue-900">Изменить</a>
                                @endauth
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

            <div class="mt-4">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
