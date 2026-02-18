<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-8">
        <h4 class="mb-3">Billing Address</h4>
        <?php if(!empty($data['general_err'])) : ?>
            <div class="alert alert-danger"><?php echo $data['general_err']; ?></div>
        <?php endif; ?>
        <form action="<?php echo URLROOT; ?>/buyer/placeOrder" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstName">First name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="" value="" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastName">Last name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="" value="" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email">Email <span class="text-muted">(Optional)</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com">
            </div>

            <div class="mb-3">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" id="phone" name="phone" placeholder="1234567890" value="<?php echo isset($data['phone']) ? $data['phone'] : ''; ?>">
                <div class="invalid-feedback">
                    <?php echo $data['phone_err']; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required>
            </div>

            <hr class="mb-4">

            <h4 class="mb-3">Payment - Secure Payment (Dummy)</h4>
            <div class="d-block my-3">
                <div class="custom-control custom-radio">
                    <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                    <label class="custom-control-label" for="credit">Credit card</label>
                </div>
                <div class="custom-control custom-radio">
                    <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" required>
                    <label class="custom-control-label" for="debit">Debit card</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cc-name">Name on card</label>
                    <input type="text" class="form-control <?php echo (!empty($data['card_name_err'])) ? 'is-invalid' : ''; ?>" id="cc-name" name="card_name" placeholder="" value="<?php echo isset($data['card_name']) ? $data['card_name'] : ''; ?>" required>
                    <small class="text-muted">Full name as displayed on card</small>
                    <div class="invalid-feedback">
                        <?php echo $data['card_name_err']; ?>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cc-number">Credit card number</label>
                    <input type="text" class="form-control <?php echo (!empty($data['card_number_err'])) ? 'is-invalid' : ''; ?>" id="cc-number" name="card_number" placeholder="" value="<?php echo isset($data['card_number']) ? $data['card_number'] : ''; ?>" required>
                    <div class="invalid-feedback">
                        <?php echo $data['card_number_err']; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="cc-expiration">Expiration</label>
                    <input type="text" class="form-control <?php echo (!empty($data['card_expiry_err'])) ? 'is-invalid' : ''; ?>" id="cc-expiration" name="card_expiry" placeholder="" value="<?php echo isset($data['card_expiry']) ? $data['card_expiry'] : ''; ?>" required>
                    <div class="invalid-feedback">
                        <?php echo $data['card_expiry_err']; ?>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="cc-cvv">CVV</label>
                    <input type="text" class="form-control <?php echo (!empty($data['card_cvv_err'])) ? 'is-invalid' : ''; ?>" id="cc-cvv" name="card_cvv" placeholder="" value="<?php echo isset($data['card_cvv']) ? $data['card_cvv'] : ''; ?>" required>
                    <div class="invalid-feedback">
                        <?php echo $data['card_cvv_err']; ?>
                    </div>
                </div>
            </div>
            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Place Order</button>
        </form>
    </div>
    
    <div class="col-md-4 mb-4">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Your cart</span>
            <span class="badge badge-secondary badge-pill"><?php echo count($data['cartItems']); ?></span>
        </h4>
        <ul class="list-group mb-3">
            <?php foreach($data['cartItems'] as $item) : ?>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0"><?php echo $item->name; ?></h6>
                        <small class="text-muted">Qty: <?php echo $item->quantity; ?></small>
                    </div>
                    <span class="text-muted">$<?php echo $item->total_price; ?></span>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between">
                <span>Total (USD)</span>
                <strong>$<?php echo $data['total']; ?></strong>
            </li>
        </ul>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
