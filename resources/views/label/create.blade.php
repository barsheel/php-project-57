<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Создание метки') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('labels.store') }}" class="mt-6 space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Имя"/>
                        <x-text-input
                            id="name"
                            name="name"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ old('name') }}"
                            autofocus
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

                    <div class="flex items-center gap-4">
                        <x-primary-button>Создать</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
