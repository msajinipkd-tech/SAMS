<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Reviews</h2>
        <a href="<?php echo URLROOT; ?>/farmer/dashboard" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="glass-card p-4">
        <?php if(empty($data['reviews'])): ?>
            <div class="text-center p-5">
                <h4 class="text-muted">No reviews yet.</h4>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Product</th>
                            <th scope="col">Buyer</th>
                            <th scope="col">Rating</th>
                            <th scope="col">Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['reviews'] as $review) : ?>
                            <tr>
                                <td class="text-muted" style="min-width: 120px;">
                                    <?php echo date('M d, Y', strtotime($review->reviewCreated)); ?>
                                </td>
                                <td class="font-weight-bold">
                                    <?php echo $review->productName; ?>
                                </td>
                                <td>
                                    <?php echo $review->userName; ?>
                                </td>
                                <td>
                                    <span class="text-warning">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <?php echo ($i <= $review->rating) ? '★' : '☆'; ?>
                                        <?php endfor; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo nl2br($review->review); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
