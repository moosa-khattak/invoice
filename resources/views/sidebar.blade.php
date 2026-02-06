<!-- Sidebar (Right Side) -->
<div class="w-full md:w-72 space-y-6">
    <!-- Download Button -->
    <button
        type="button"
        class="w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 px-4 rounded-lg shadow transition flex items-center justify-center gap-2"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            viewBox="0 0 20 20"
            fill="currentColor"
        >
            <path
                fill-rule="evenodd"
                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                clip-rule="evenodd"
            />
        </svg>
        Download
    </button>

    <div class="border-t border-gray-200 my-6"></div>

    <!-- Theme Selector -->
    <div>
        <label class="block text-gray-500 mb-2 font-medium">Theme</label>
        <select
            class="w-full border border-gray-200 rounded-lg p-3 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
        >
            <option>Classic</option>
            <option>Modern</option>
        </select>
    </div>

    <!-- Currency Selector -->
    <div>
        <label class="block text-gray-500 mb-2 font-medium">Currency</label>
        <select
            name="currency"
            id="currency-selector"
            class="w-full border border-gray-200 rounded-lg p-3 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
        >
            @foreach ($currencies as $currency)
                <option
                    value="{{ $currency->code }}"
                    data-symbol="{{ $currency->symbol }}"
                    {{ old('currency', 'USD') == $currency->code ? 'selected' : '' }}
                >
                    {{ $currency->code }} ({{ $currency->symbol }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="pt-6 space-y-3">
        <button
            type="submit"
            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-lg shadow-sm transition duration-200"
        >
            Save Invoice
        </button>

        <div class="text-center">
            <a
                href="{{ route('allinvoices') }}"
                class="text-sm font-medium text-gray-500 hover:text-teal-600 transition duration-150"
            >
                View All Invoices
            </a>
        </div>
    </div>
</div>
