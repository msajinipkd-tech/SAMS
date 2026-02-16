<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="fade-in">
    <a href="<?php echo URLROOT; ?>/buyer/shop" class="btn btn-secondary mb-3"><i class="fa fa-arrow-left"></i> Back to Shop</a>
    
    <div class="row">
        <!-- Product Details -->
        <div class="col-md-6 mb-4">
            <div class="glass-card p-4 h-100">
                <div class="text-center mb-4">
                     <div style="width: 100%; height: 300px; border-radius: 12px; background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 6rem;">
                        🛍️
                    </div>
                </div>
                <h2 class="text-primary"><?php echo $data['product']->name; ?></h2>
                <div class="mb-2">
                    <span class="badge badge-info"><?php echo $data['product']->category; ?></span>
                    <span class="text-warning ml-2">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <?php if($i <= $data['avgRating']): ?>
                                ★
                            <?php else: ?>
                                ☆
                            <?php endif; ?>
                        <?php endfor; ?>
                        (<?php echo $data['avgRating']; ?> / 5)
                    </span>
                </div>
                <h3 class="font-weight-bold my-3">₹<?php echo $data['product']->price; ?></h3>
                <p class="lead"><?php echo $data['product']->description; ?></p>
                
                <div class="mt-4">
                    <?php if($data['product']->quantity > 0): ?>
                        <p class="text-success"><i class="fa fa-check-circle"></i> In Stock (<?php echo $data['product']->quantity; ?> available)</p>
                        <form action="<?php echo URLROOT; ?>/buyer/addToCart" method="post" class="form-inline">
                            <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                            <label class="mr-2">Quantity:</label>
                            <input type="number" name="quantity" class="form-control mr-2" value="1" min="1" max="<?php echo $data['product']->quantity; ?>" style="width: 80px;">
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-danger" disabled>Out of Stock</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="col-md-6">
            <div class="glass-card p-4 h-100">
                <h3 class="mb-4">Customer Reviews</h3>
                
                <!-- Review Form -->
                <div class="card mb-4 border-0 bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Write a Review</h5>
                        <form action="<?php echo URLROOT; ?>/buyer/addReview" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                            <div class="form-group">
                                <label>Rating</label>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 stars">★</label>
                                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars">★</label>
                                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars">★</label>
                                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars">★</label>
                                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star">★</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Review</label>
                                <textarea name="review" class="form-control" rows="3" required placeholder="Share your experience..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Submit Review</button>
                        </form>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="reviews-list" style="max-height: 500px; overflow-y: auto;">
                    <?php if(empty($data['reviews'])): ?>
                        <p class="text-muted text-center">No reviews yet. Be the first to review!</p>
                    <?php else: ?>
                        <?php foreach($data['reviews'] as $review): ?>
                            <div class="media mb-3 border-bottom pb-3">
                                <div class="mr-3 mt-1" style="width: 40px; height: 40px; border-radius: 50%; background: #ddd; display: flex; align-items: center; justify-content: center;">
                                    User
                                </div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1 font-weight-bold">
                                         <?php echo $review->username; ?> 
                                         <small class="text-muted ml-2"><?php echo date('M d, Y', strtotime($review->reviewCreated)); ?></small>
                                    </h6>
                                    <div class="text-warning mb-1" style="font-size: 0.9rem;">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <?php echo ($i <= $review->rating) ? '★' : '☆'; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="mb-0 text-muted"><?php echo $review->review; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
