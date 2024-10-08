<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        body {
            background-color: #f0f0f2;
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
            padding: 50px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 2px 3px 7px 2px rgba(0, 0, 0, 0.02);;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-6 col-lg-12">
            <div class="form-group">
                <h2>Payment Form</h2>
                <form id="payment-form" action="/payment" method="POST">
                    @csrf
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control mb-2" id="amount" name="amount" required min="10000"
                           value="10000">
                    <p style="background-color: #51d0ea; padding: 10px; font-size: 10px">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur eligendi est ex ipsum vero!
                        In maiores nisi praesentium quod repudiandae sequi, velit voluptate. Est ipsam, laudantium
                        magnam quam reiciendis veniam. Ab accusamus cum debitis facilis totam? Alias, animi assumenda
                        atque blanditiis, cum dolor ducimus eos expedita ipsa provident, quod totam vitae. Atque deserunt
                        dolores, inventore necessitatibus quas quasi tenetur voluptatum.
                    </p>
                    <button type="submit" class="btn btn-primary">Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#payment-form').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/payment'
                , method: 'POST'
                , data: $(this).serialize()
                , success: function (response) {
                    snap.pay(response.snap_token, {
                        onSuccess: function (result) {
                            alert('Payment success!');
                        }
                        , onPending: function (result) {
                            alert('Waiting for your payment!');
                        }
                        , onError: function (result) {
                            alert('Payment failed!');
                        }
                        , onClose: function () {
                            alert('You closed the popup without finishing the payment');
                        }
                    });
                }
                , error: function (response) {
                    alert('Payment initiation failed!');
                }
            });
        });
    });

</script>
</body>
</html>
