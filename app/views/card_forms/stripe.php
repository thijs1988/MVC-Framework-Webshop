<?php
use Core\FH;
?>

<?php $this->setSiteTitle('Checkout'); ?>
<?php $this->start('body') ?>
<div class="row">
  <div class="col-md-8">
    <h3>Purchase Detials</h3>

<form action="<?=PROOT?>cart/checkout/<?=$this->cartId?>" method="post" id="payment-form">
  <?=FH::csrfInput()?>
  <input type="hidden" name="step" value="2"/>
  <?= FH::hiddenInput('name', $this->tx->name)?>
  <?= FH::hiddenInput('shipping_address1', $this->tx->shipping_address1)?>
  <?= FH::hiddenInput('shipping_address2', $this->tx->shipping_address2)?>
  <?= FH::hiddenInput('shipping_city', $this->tx->shipping_city)?>
  <?= FH::hiddenInput('shipping_state', $this->tx->shipping_state)?>
  <?= FH::hiddenInput('shipping_zip', $this->tx->shipping_zip)?>
  <div class="form-group col-md-12">
    <label for="card-element" class="control-label">
      Credit or debit card
    </label>
    <div id="card-element">
      <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert" class="text-danger col-md-12"></div>
  </div>
  <div class="col-md-12">
    <button class="btn btn-lg btn-ptimary">Submit Payment</button>
  </div>
</form>
</div>
<div class="col-md-4"><?php $this->partial('cart', 'product_preview')?></div>
</div>


<script src="https://js.stripe.com/v3/"></script>
<script>
// Create a Stripe client.
var stripe = Stripe('<?=STRIPE_PUBLIC?>');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
base: {
  color: '#32325d',
  fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
  fontSmoothing: 'antialiased',
  fontSize: '16px',
  '::placeholder': {
    color: '#aab7c4'
  }
},
invalid: {
  color: '#fa755a',
  iconColor: '#fa755a'
}
};

// Create an instance of the card Element.
var card = elements.create('card', {style: style});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

// Handle real-time validation errors from the card Element.
card.on('change', function(event) {
var displayError = document.getElementById('card-errors');
if (event.error) {
  displayError.textContent = event.error.message;
} else {
  displayError.textContent = '';
}
});

// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
event.preventDefault();

stripe.createToken(card).then(function(result) {
  if (result.error) {
    // Inform the user if there was an error.
    var errorElement = document.getElementById('card-errors');
    errorElement.textContent = result.error.message;
  } else {
    // Send the token to your server.
    stripeTokenHandler(result.token);
  }
});
});

// Submit the form with the token ID.
function stripeTokenHandler(token) {
// Insert the token ID into the form so it gets submitted to the server
var form = document.getElementById('payment-form');
var hiddenInput = document.createElement('input');
hiddenInput.setAttribute('type', 'hidden');
hiddenInput.setAttribute('name', 'stripeToken');
hiddenInput.setAttribute('value', token.id);
form.appendChild(hiddenInput);

// Submit the form
form.submit();
}
</script>
<?php $this->end()?>
