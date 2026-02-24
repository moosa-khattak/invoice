<!-- Sidebar (Right Side) -->
<div class="w-full md:w-72 space-y-6 ">
    <!-- Download Button -->

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
                    {{ old('currency', $invoice->currency ?? 'USD') == $currency->code ? 'selected' : '' }}
                >
                    {{ $currency->code }} ({{ $currency->symbol }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="pt-6 space-y-3">
        <button
            type="submit"
            class="w-full cursor-pointer bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-lg shadow-sm transition duration-200"
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
