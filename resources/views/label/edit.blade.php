<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Изменение метки') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('labels.update', $label) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" :value="__('Имя')"/>
                        <x-text-input id="name" name="name" type="text" value="{{ $label->name }}"
                                      class="mt-1 block w-full" required autofocus/>
                        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Описание')"/>
                        <x-textarea-input id="description" name="description" :value="$label->description" cols="30"
                                          rows="6"
                                          class="mt-1 block w-full"></x-textarea-input>
                        <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Обновить') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
