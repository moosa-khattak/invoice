<!-- Invoice Paper (Left Side) -->
<div
    class="flex-1 bg-white p-8 md:p-12 rounded-lg shadow-sm border border-gray-200 w-full"
>
    <!-- Top Section: Logo & Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start gap-8 mb-10"
    >
        <!-- Logo Placeholder -->
        <div
            class="w-full md:w-64 max-w-xs mx-auto md:mx-0 relative group/logo"
        >
            <label
                class="border border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 transition rounded-lg h-32 md:h-40 flex flex-col items-center justify-center cursor-pointer overflow-hidden"
            >
                <div
                    id="logo-placeholder"
                    class="flex flex-col items-center justify-center {{ old('logo_base64') ? 'hidden' : '' }}"
                >
                    <span
                        class="text-3xl text-gray-400 mb-2 group-hover:text-gray-500"
                    >
                        +
                    </span>
                    <span
                        class="text-gray-400 font-medium group-hover:text-gray-500"
                    >
                        Add Your Logo
                    </span>
                </div>
                <img
                    id="logo-preview"
                    src="{{ old('logo_base64') }}"
                    class="{{ old('logo_base64') ? '' : 'hidden' }} h-full w-full object-contain"
                />
                <input
                    type="file"
                    name="logo"
                    accept="image/*"
                    id="logo-input"
                    class="hidden"
                />
                <input
                    type="hidden"
                    name="logo_base64"
                    id="logo-base64-input"
                    value="{{ old('logo_base64') }}"
                />
            </label>
            <!-- Remove Button -->
            <button
                type="button"
                id="remove-logo"
                class="{{ old('logo_base64') ? '' : 'hidden' }} absolute -top-3 -right-3 bg-white text-gray-500 hover:text-red-600 rounded-full h-8 w-8 shadow-lg border border-gray-200 items-center justify-center transition-all duration-200 hover:scale-110 z-10"
                title="Remove Logo"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 translate-x-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <!-- Invoice Header -->
        <div class="w-full md:w-64 text-center md:text-right">
            <h1
                class="text-4xl md:text-5xl font-light text-gray-800 tracking-wide mb-4"
            >
                INVOICE
            </h1>
            <div class="relative">
                <span class="absolute left-18 top-2.5 text-gray-400 font-bold">
                    #
                </span>
                <input
                    type="text"
                    name="invoice_number"
                    value="{{ old('invoice_number') }}"
                    class="border border-gray-200 rounded-lg py-2 pr-3 text-right focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>
        </div>
    </div>

    <!-- Middle Section: From & Meta Data -->
    <div class="flex flex-col md:flex-row gap-12">
        <!-- Left Column (From, Bill To, Ship To) -->
        <div class="flex-1 space-y-8">
            <!-- From -->
            <div>
                <input
                    type="text"
                    name="from"
                    value="{{ old('from') }}"
                    placeholder="Who is this from?"
                    class="w-full border border-gray-200 rounded-lg p-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>

            <!-- Bill To & Ship To Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-500 mb-2 font-medium">
                        Bill To
                    </label>
                    <input
                        type="text"
                        name="bill_to"
                        value="{{ old('bill_to') }}"
                        placeholder="Who is this to?"
                        class="w-full border border-gray-200 rounded-lg p-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                    />
                </div>
                <div>
                    <label class="block text-gray-500 mb-2 font-medium">
                        Ship To
                    </label>
                    <input
                        type="text"
                        name="ship_to"
                        value="{{ old('ship_to') }}"
                        placeholder="(optional)"
                        class="w-full border border-gray-200 rounded-lg p-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                    />
                </div>
            </div>
        </div>

        <!-- Right Column (Dates & Meta) -->
        <div class="w-full md:w-80 space-y-4">
            <!-- Date -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                <label class="sm:w-32 sm:text-right text-gray-500">Date</label>
                <input
                    type="date"
                    name="date"
                    value="{{ old('date') }}"
                    class="flex-1 border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>

            <!-- Payment Terms -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                <label class="sm:w-32 sm:text-right text-gray-500">
                    Payment Terms
                </label>
                <input
                    type="text"
                    name="payment_terms"
                    value="{{ old('payment_terms') }}"
                    class="flex-1 border border-gray-200 rounded-lg py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>

            <!-- Due Date -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                <label class="sm:w-32 sm:text-right text-gray-500">
                    Due Date
                </label>
                <input
                    type="date"
                    name="due_date"
                    value="{{ old('due_date') }}"
                    class="flex-1 border border-gray-200 rounded-lg py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>

            <!-- PO Number -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                <label class="sm:w-32 sm:text-right text-gray-500">
                    PO Number
                </label>
                <input
                    type="text"
                    name="po"
                    value="{{ old('po') }}"
                    class="flex-1 border border-gray-200 rounded-lg py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>
        </div>
    </div>

    @section('script')
        <script src="{{ asset('js/invoice.js') }}"></script>
    @endsection
</div>
