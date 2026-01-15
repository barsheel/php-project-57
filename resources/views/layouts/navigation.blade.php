<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Логотип и ссылки -->
            <div class="flex items-center">
                <a href="/" class="shrink-0 flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                </a>

                <!-- Главное меню -->
                <div class="hidden sm:flex space-x-8 sm:ms-10">
                    <x-nav-link :href="route('task_statuses.index')"
                                :active="request()->routeIs('task_statuses.index')">
                        Статусы
                    </x-nav-link>

                    <x-nav-link :href="route('tasks.index')"
                                :active="request()->routeIs('tasks.index')">
                        Задачи
                    </x-nav-link>

                    <x-nav-link :href="route('labels.index')"
                                :active="request()->routeIs('labels.index')">
                        Метки
                    </x-nav-link>
                </div>
            </div>

            <!-- Вход и выход, пользователь -->
            <div class="flex items-center space-x-2">
                @auth
                    <!-- Выход -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-primary-button type="submit">
                            Выход
                        </x-primary-button>
                    </form>
                @else
                    <!-- Вход / Регистрация всегда видны -->
                    <a href="{{ route('login') }}">
                        <x-primary-button>
                            Войти
                        </x-primary-button>
                    </a>

                    <a href="{{ route('register') }}">
                        <x-primary-button>
                            Регистрация
                        </x-primary-button>
                    </a>
                @endauth

</nav>
