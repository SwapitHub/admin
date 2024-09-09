<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>form</title>
    <style>
        .container .button-container {
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        .container .button-container button {
            background-color: #xxxxxx;
            border:
                border-radius: xx;
            color: white;
            font-size: xx;
            display: xxxx;
            height: xx;
            width: xx;
        }

        #paymentRequestButton. {
            width: 85%;
            height: 40px;
            margin: 0 auto;
        }

        @media (min-width: 200px) {
            body {
                width: auto;
            }
        }

        @media (min-width: 600px) {
            body {
                width: 600px
            }
        }

        .hr {
            width: 100%;
            height: 10px;
            border-bottom: 1px solid black;
            text-align: center;
            margin: 20px 0;
        }

        .hr span {
            font-size: 10px;
            background-color: #FFF;
            padding: 0 10px;
        }

        .clover-privacy-link {
            font-family: Roboto, "Open Sans", sans-serif;
        }

        section {
            flex-wrap: wrap;
            width: 100%;
            justify-content: space-evenly;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="/charge" method="post" id="payment-form">
            <div class="form-row top-row">
                <div id="amount" class="field card-number">
                    <input name="amount" placeholder="Amount">
                </div>
            </div>

            <div class="form-row top-row">
                <div id="card-number" class="field card-number"></div>
                <div class="input-errors" id="card-number-errors" role="alert"></div>
            </div>

            <div class="form-row">
                <div id="card-date" class="field third-width"></div>
                <div class="input-errors" id="card-date-errors" role="alert"></div>
            </div>

            <div class="form-row">
                <div id="card-cvv" class="field third-width"></div>
                <div class="input-errors" id="card-cvv-errors" role="alert"></div>
            </div>

            <div class="form-row">
                <div id="card-postal-code" class="field third-width"></div>
                <div class="input-errors" id="card-postal-code-errors" role="alert"></div>
            </div>

            <div id="card-response" role="alert"></div>
            <div class="button-container">
                <button>Submit Payment</button>
            </div>
        </form>
    </div>


</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.polyfill.io/v3/polyfill.min.js"></script>
<script src="https://checkout.sandbox.dev.clover.com/sdk.js"></script>


<script>
    const clover = new Clover('45a9c123-c9ae-e33f-6ec4-474eb894c5ec', {
        merchantId: '2QMA6A0REPAA2'
    });
    const elements = clover.elements();


    const cardNumber = elements.create('CARD_NUMBER');
    const cardDate = elements.create('CARD_DATE');
    const cardCvv = elements.create('CARD_CVV');
    const cardPostalCode = elements.create('CARD_POSTAL_CODE');

    cardNumber.mount('#card-number');
    cardDate.mount('#card-date');
    cardCvv.mount('#card-cvv');
    cardPostalCode.mount('#card-postal-code');

    // card response element id
    const cardResponse = document.getElementById('card-response');

    // get card errors by element id
    const displayCardNumberError = document.getElementById('card-number-errors');
    const displayCardDateError = document.getElementById('card-date-errors');
    const displayCardCvvError = document.getElementById('card-cvv-errors');
    const displayCardPostalCodeError = document.getElementById('card-postal-code-errors');

    // Handle real-time validation errors from the card element
    cardNumber.addEventListener('change', function(event) {
        console.log(`cardNumber changed ${JSON.stringify(event)}`);
        displayCardNumberError.innerHTML = event.CARD_NUMBER.error || '';
    });
    cardNumber.addEventListener('blur', function(event) {
        console.log(`cardNumber blur ${JSON.stringify(event)}`);
        displayCardNumberError.innerHTML = event.CARD_NUMBER.error || '';
    });


    cardDate.addEventListener('blur', function(event) {
        console.log(`cardNumber blur ${JSON.stringify(event)}`);
        displayCardDateError.innerHTML = event.CARD_DATE.error || '';
    });
    cardDate.addEventListener('change', function(event) {
        console.log(`cardNumber blur ${JSON.stringify(event)}`);
        displayCardDateError.innerHTML = event.CARD_DATE.error || '';
    });

    cardCvv.addEventListener('change', function(event) {
        console.log(`cardDate changed ${JSON.stringify(event)}`);
        displayCardCvvError.innerHTML = event.CARD_CVV.error || '';

    });
    cardCvv.addEventListener('blur', function(event) {
        console.log(`cardDate changed ${JSON.stringify(event)}`);
        displayCardCvvError.innerHTML = event.CARD_CVV.error || '';

    });

    cardPostalCode.addEventListener('blur', function(event) {
        console.log(`cardDate blur ${JSON.stringify(event)}`);
        displayCardPostalCodeError.innerHTML = event.CARD_POSTAL_CODE.error || '';
    });
    cardPostalCode.addEventListener('change', function(event) {
        console.log(`cardDate blur ${JSON.stringify(event)}`);
        displayCardPostalCodeError.innerHTML = event.CARD_POSTAL_CODE.error || '';
    });


    // Listen for form submission
    const form = document.getElementById('payment-form')
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        // Use the iframe's tokenization method with the user-entered card details
        clover.createToken()
            .then(async function(result) {
                if (result.errors) {
                    Object.values(result.errors).forEach(function(value) {
                        displayError.textContent = value;
                    });
                } else {
                    const token = await result.token;
                    console.log("find token", token);
                }
            }).catch(function(data) {
                console.log(data);
            })
    });

    function cloverTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'cloverToken');
        hiddenInput.setAttribute('value', token);
        form.appendChild(hiddenInput);
        form.submit();
    }
</script>

</html>
