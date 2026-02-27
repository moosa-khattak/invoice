<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT SIDE -->
            <div class="flex">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Invoice<span class="text-teal-600">Pro</span></span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden sm:ml-8 sm:flex sm:space-x-8">

                    <a href="{{ url('/') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium
                       {{ Request::is('/') ? 'border-teal-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                        Create Invoice
                    </a>

                    <a href="{{ route('allinvoices') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium
                       {{ Request::is('all-invoices*') ? 'border-teal-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                        All Invoices
                    </a>

                </div>
            </div>

            <!-- RIGHT SIDE (Auth Links Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:space-x-6">

                @guest
                    <a href="{{route('login')}}"
                       class="text-sm font-medium text-gray-600 hover:text-teal-600">
                        Login
                    </a>

                    <a href="{{route('register')}}"
                       class="px-4 py-2 bg-teal-600 text-white text-sm rounded-lg hover:bg-teal-700 transition">
                        Register
                    </a>
                @endguest

                @auth
                    <span class="text-sm text-gray-700">
                        Hello, {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm text-red-600 hover:text-red-800">
                            Logout
                        </button>
                    </form>
                @endauth

            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center sm:hidden">
                <button onclick="toggleMobileMenu()"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="hidden sm:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">

            <a href="{{ route('invoice.create') }}"
               class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium
               {{ Request::is('invoice.create') ? 'bg-teal-50 border-teal-500 text-teal-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }}">
                Create Invoice
            </a>

            <a href="{{ route('allinvoices') }}"
               class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium
               {{ Request::is('allinvoices') ? 'bg-teal-50 border-teal-500 text-teal-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }}">
                All Invoices
            </a>

            @guest
                <a href="{{ route('login') }}"
                   class="block pl-3 pr-4 py-2 text-gray-600 hover:bg-gray-50">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="block pl-3 pr-4 py-2 text-teal-600 font-medium hover:bg-gray-50">
                    Register
                </a>
            @endguest

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left pl-3 pr-4 py-2 text-red-600 hover:bg-gray-50">
                        Logout
                    </button>
                </form>
            @endauth

        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    }
</script>