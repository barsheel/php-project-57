<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Создать задачу
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('tasks.store') }}" class="mt-6 space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Имя"/>
                        <x-text-input
                            id="name"
                            name="name"
                            type="text"
                            class="mt-1 block w-full"
                            autofocus
                            value="{{ old('name') }}"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                    </div>

                    <div>
                        <x-input-label for="description" value="Описание"/>
                        <x-textarea-input
                            id="description"
                            name="description"
                            cols="30"
                            rows="6"
                            class="mt-1 block w-full"
                        >{{ old('description') }}</x-textarea-input>
                        <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                    </div>

                    <div>
                        <x-input-label for="status_id" value="Статус"/>
                        <select id="status_id" name="status_id"
                                class="mt-1 block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value=""></option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status_id')"/>
                    </div>

                    <div>
                        <x-input-label for="assigned_to_id" value="Исполнитель"/>
                        <select id="assigned_to_id" name="assigned_to_id"
                                class="mt-1 block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value=""></option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('assigned_to_id')"/>
                    </div>

                    <div>
                        <x-input-label for="labels[]" value="Метки"/>
                        <select id="labels[]" name="labels[]" multiple
                                class="mt-1 block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach($labels as $label)
                                <option value="{{ $label->id }}"
                                    @selected(in_array($label->id, old('labels', [])))>
                                        {{ $label->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('labels')"/>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Создать</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
