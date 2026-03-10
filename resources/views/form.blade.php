<!-- Invoice Paper (Left Side) -->
<div class="flex-1 bg-white p-8 md:p-12 rounded-lg shadow-sm border border-gray-200 w-full">

    <!-- Top Section -->
    <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-10">

        <!-- Logo Upload -->
        <div class="w-full md:w-64 max-w-xs mx-auto md:mx-0 relative group/logo">

            <label
                class="border border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 transition rounded-lg h-32 md:h-40 flex flex-col items-center justify-center cursor-pointer overflow-hidden">

                <div id="logo-placeholder"
                    class="flex flex-col items-center justify-center {{ old('logo_base64') || (isset($invoice) && $invoice->logo_path) ? 'hidden' : '' }}">

                    <span class="text-3xl text-gray-400 mb-2 group-hover:text-gray-500">+</span>
                    <span class="text-gray-400 font-medium group-hover:text-gray-500">
                        Add Your Logo
                    </span>

                </div>

                <img
                    id="logo-preview"
                    src="{{ old('logo_base64') ?: (isset($invoice) && $invoice->logo_path ? asset('storage/' . $invoice->logo_path) : '') }}"
                    class="{{ old('logo_base64') || (isset($invoice) && $invoice->logo_path) ? '' : 'hidden' }} h-full w-full object-contain" />

                <input type="file" name="logo" accept="image/*" id="logo-input" class="hidden" />

                <input
                    type="hidden"
                    name="logo_base64"
                    id="logo-base64-input"
                    value="{{ old('logo_base64') }}" />

            </label>

            <!-- Remove Logo -->
            <button
                type="button"
                id="remove-logo"
                class="{{ old('logo_base64') || (isset($invoice) && $invoice->logo_path) ? '' : 'hidden' }} absolute -top-3 -right-3 bg-white text-gray-500 hover:text-red-600 rounded-full h-8 w-8 shadow-lg border border-gray-200 items-center justify-center transition-all duration-200 hover:scale-110 z-10">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 translate-x-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>

            </button>

        </div>

        <!-- Invoice Header -->
        <div class="w-full md:w-64 text-center md:text-right">

            <h1 class="text-4xl md:text-5xl font-light text-gray-800 tracking-wide mb-4">
                INVOICE
            </h1>

            <div class="relative">

                <span class="absolute right-2 top-2.5 text-gray-400 font-bold">
                    #
                </span>

                <x-input
                    name="invoice_number"
                    :value="$invoice->invoice_number ?? ($nextInvoiceNumber ?? '')"
                    :readonly="true" />

            </div>

        </div>

    </div>

    <!-- Middle Section -->
    <div class="flex flex-col md:flex-row gap-12">

        <!-- Left Column -->
        <div class="flex-1 space-y-8">

            <!-- From -->
            <x-input
                name="from"
                placeholder="Who is this from?"
                :value="$invoice->from ?? ''" />

            <!-- Bill To + Ship To -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <x-input
                    name="bill_to"
                    label="Bill To"
                    placeholder="Who is this to?"
                    :value="$invoice->bill_to ?? ''" />

                <x-input
                    name="ship_to"
                    label="Ship To"
                    placeholder="(optional)"
                    :value="$invoice->ship_to ?? ''" />
                    
                </div>
                <x-input
                    name="po_number"
                    label="PO Number"
                    :value="$invoice->po_number ?? ''" />

        </div>

        <!-- Right Column -->
        <div class="w-full md:w-80 space-y-4">

            <!-- Date -->
            <x-input
                name="date"
                label="Date"
                type="date"
                :value="isset($invoice) && $invoice->date ? $invoice->date->format('Y-m-d') : ''" />

            <!-- Payment Terms -->
            <x-input
                name="payment_terms"
                label="Payment Terms"
                :value="$invoice->payment_terms ?? ''" />

            <!-- Due Date -->
            <x-input
                name="due_date"
                label="Due Date"
                type="date"
                :value="isset($invoice) && $invoice->due_date ? $invoice->due_date->format('Y-m-d') : ''" />

           

        </div>

    </div>

    @push('scripts')
    <script src="{{ asset('js/invoice.js') }}"></script>
    <script src="{{ asset('js/form.js') }}"></script>
    @endpush

</div>