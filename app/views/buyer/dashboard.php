<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="fade-in">
    <!-- Hero Section -->
    <div class="hero-section">
        <h2>Welcome Back, Buyer!</h2>
        <p class="lead mb-4">Discover the freshest produce and agricultural products directly from farmers.</p>
        
        <div class="hero-search-container">
            <form action="<?php echo URLROOT; ?>/buyer/shop" method="get">
                <div class="input-group">
                    <input type="text" name="search" class="form-control border-0" placeholder="What are you looking for today? (e.g., Spinach, Seeds...)" style="padding: 1rem;">
                    <button type="submit" class="btn btn-primary" style="border-radius: 0 10px 10px 0;">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Access / New Arrivals -->
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Quick Access <small class="text-muted" style="font-size: 0.6em; vertical-align: middle;">(New Arrivals)</small></h3>
            <a href="<?php echo URLROOT; ?>/buyer/shop" class="btn btn-sm btn-outline-primary" style="border-radius: 20px;">View All Products</a>
        </div>
        
        <?php if(empty($data['recentProducts'])): ?>
            <div class="col-12">
                <div class="alert alert-info">No products available at the moment. Check back soon!</div>
            </div>
        <?php else: ?>
            <?php foreach($data['recentProducts'] as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card product-card-quick h-100">
                        <!-- Placeholder for Product Image if we had one, for now using a colored block or icon -->
                        <div style="height: 150px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); display: flex; align-items: center; justify-content: center; color: #6C5CE7;">
                             <span style="font-size: 3rem;">📦</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate"><?php echo $product->name; ?></h5>
                            <p class="card-text small text-muted mb-2"><?php echo $product->category; ?></p>
                            <h6 class="text-primary mb-3">₹<?php echo $product->price; ?> / <?php echo $product->quantity; ?></h6>
                            
                            <div class="mt-auto">
                                <form action="<?php echo URLROOT; ?>/buyer/addToCart" method="post" class="d-flex">
                                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Shop by Category -->
    <div class="row mb-5">
        <div class="col-md-12 mb-3">
            <h3>Explore Categories</h3>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="category-card" onclick="window.location.href='<?php echo URLROOT; ?>/buyer/shop?category=Vegetables'">
                <span class="category-icon">🥦</span>
                <h5>Vegetables</h5>
                <p class="text-muted small mb-0">Fresh & Organic</p>
            </div>
        </div>
         <div class="col-md-3 mb-3">
            <div class="category-card" onclick="window.location.href='<?php echo URLROOT; ?>/buyer/shop?category=Seeds'">
                <span class="category-icon">🌱</span>
                <h5>Seeds</h5>
                <p class="text-muted small mb-0">High Quality Yield</p>
            </div>
        </div>
         <div class="col-md-3 mb-3">
            <div class="category-card" onclick="window.location.href='<?php echo URLROOT; ?>/buyer/shop?category=Plants'">
                <span class="category-icon">🌿</span>
                <h5>Plants</h5>
                <p class="text-muted small mb-0">Garden & Farm</p>
            </div>
        </div>
         <div class="col-md-3 mb-3">
            <div class="category-card" onclick="window.location.href='<?php echo URLROOT; ?>/buyer/shop?category=Pesticides'">
                <span class="category-icon">🛡️</span>
                <h5>Pesticides</h5>
                <p class="text-muted small mb-0">Crop Protection</p>
            </div>
        </div>
    </div>
    
    <!-- Account Management (Styled) -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
             <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
                <div>
                    <h5 class="mb-1">My Orders</h5>
                    <p class="text-muted mb-0 small">Track active & past orders</p>
                </div>
                <a href="<?php echo URLROOT; ?>/buyer/orders" class="btn btn-secondary btn-sm">View Orders</a>
             </div>
        </div>
         <div class="col-md-4 mb-3">
             <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
                <div>
                    <h5 class="mb-1">Shopping Cart</h5>
                    <p class="text-muted mb-0 small">Manage your items</p>
                </div>
                <a href="<?php echo URLROOT; ?>/buyer/cart" class="btn btn-success btn-sm">Go to Cart</a>
             </div>
        </div>
        <div class="col-md-4 mb-3">
             <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
                <div>
                    <h5 class="mb-1">Feedback</h5>
                    <p class="text-muted mb-0 small">Share your thoughts</p>
                </div>
                <a href="<?php echo URLROOT; ?>/buyer/feedback" class="btn btn-info btn-sm">Give Feedback</a>
             </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
