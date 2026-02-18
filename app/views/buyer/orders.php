<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <?php flash('order_message'); ?>
        <h1>My Orders</h1>
        <?php if(empty($data['orders'])): ?>
            <p>You have no orders yet. <a href="<?php echo URLROOT; ?>/buyer/shop">Go Shopping</a></p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order Date</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['orders'] as $order) : ?>
                        <tr>
                            <td><?php echo $order->created_at; ?></td>
                            <td><?php echo $order->product_name; ?></td>
                            <td><?php echo $order->quantity; ?></td>
                            <td>$<?php echo $order->total_price; ?></td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $order->status == 'pending' ? 'warning' : 
                                        ($order->status == 'completed' ? 'success' : 
                                        ($order->status == 'cancelled' ? 'danger' : 'secondary')); 
                                ?>">
                                    <?php echo ucfirst($order->status); ?>
                                </span>
                            </td>
                            <td>
                                <?php if($order->status == 'pending'): ?>
                                    <form action="<?php echo URLROOT; ?>/buyer/cancelOrder/<?php echo $order->id; ?>" method="post" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel Order</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
