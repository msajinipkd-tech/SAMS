<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <h1>Shopping Cart</h1>
        <?php if(empty($data['cartItems'])): ?>
            <p>Your cart is empty. <a href="<?php echo URLROOT; ?>/buyer/shop">Go Shopping</a></p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['cartItems'] as $item) : ?>
                        <tr>
                            <td><?php echo $item->name; ?></td>
                            <td>$<?php echo $item->price; ?></td>
                            <td><?php echo $item->quantity; ?></td>
                            <td>$<?php echo $item->total_price; ?></td>
                            <td>
                                <form action="<?php echo URLROOT; ?>/buyer/removeFromCart/<?php echo $item->id; ?>" method="post" onsubmit="return confirm('Are you sure you want to remove this product from your cart?');">
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                        <td><strong>$<?php echo $data['total']; ?></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-right">
                <a href="<?php echo URLROOT; ?>/buyer/checkout" class="btn btn-success">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
