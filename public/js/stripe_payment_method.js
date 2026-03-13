 const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();

    const style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
            fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    const card = elements.create('card', {
        style: style
    });
    card.mount('#card-element');

    const form = document.getElementById('stripe-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const errorDisplay = document.getElementById('card-errors');

    submitButton.addEventListener('click', async (e) => {
        setLoading(true);

        const {
            paymentIntent,
            error
        } = await stripe.confirmCardPayment("{{ $clientSecret }}", {
            payment_method: {
                card: card,
                billing_details: {
                    name: "{{ addslashes($invoice->bill_to) }}"
                }
            }
        });

        if (error) {
            errorDisplay.textContent = error.message;
            errorDisplay.classList.remove('hidden');
            setLoading(false);
        } else {
            if (paymentIntent.status === 'succeeded') {
                document.getElementById('payment-intent-id').value = paymentIntent.id;
                form.submit();
            }
        }
    });

    function setLoading(isLoading) {
        if (isLoading) {
            submitButton.disabled = true;
            spinner.classList.remove('hidden');
            buttonText.classList.add('hidden');
        } else {
            submitButton.disabled = false;
            spinner.classList.add('hidden');
            buttonText.classList.remove('hidden');
        }
    }

    function switchMethod(method) {
        const cardSection = document.getElementById('card-section');
        const bankSection = document.getElementById('bank-section');
        const btnCard = document.getElementById('btn-card');
        const btnBank = document.getElementById('btn-bank');

        if (method === 'card') {
            cardSection.classList.remove('hidden');
            bankSection.classList.add('hidden');

            // UI States
            btnCard.classList.add('border-indigo-600', 'bg-indigo-50');
            btnCard.classList.remove('border-gray-200');
            btnCard.querySelector('svg').classList.add('text-indigo-600');
            btnCard.querySelector('span').classList.add('text-indigo-900');

            btnBank.classList.remove('border-indigo-600', 'bg-indigo-50');
            btnBank.classList.add('border-gray-200');
            btnBank.querySelector('svg').classList.remove('text-indigo-600');
            btnBank.querySelector('span').classList.remove('text-indigo-900');
        } else {
            cardSection.classList.add('hidden');
            bankSection.classList.remove('hidden');

            // UI States
            btnCard.classList.remove('border-indigo-600', 'bg-indigo-50');
            btnCard.classList.add('border-gray-200');
            btnCard.querySelector('svg').classList.remove('text-indigo-600');
            btnCard.querySelector('span').classList.remove('text-indigo-900');

            btnBank.classList.add('border-indigo-600', 'bg-indigo-50');
            btnBank.classList.remove('border-gray-200');
            btnBank.querySelector('svg').classList.add('text-indigo-600');
            btnBank.querySelector('span').classList.add('text-indigo-900');
        }
    }