<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="fade-in">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-3 mb-4">
            <div class="glass-card p-4">
                <h4 class="mb-3">Filter & Search</h4>
                <form action="<?php echo URLROOT; ?>/buyer/shop" method="get">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Search</label>
                         <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Product name..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                            <button class="btn btn-primary" type="submit">🔍</button>
                        </div>
                    </div>
                </form>

                <h5 class="mt-4 mb-3">Categories</h5>
                <div class="list-group list-group-flush" style="border-radius: 12px; overflow: hidden;">
                    <a href="<?php echo URLROOT; ?>/buyer/shop" class="list-group-item list-group-item-action <?php echo $data['selectedCategory'] == null ? 'active' : ''; ?>" style="background-color: <?php echo $data['selectedCategory'] == null ? 'var(--primary-color)' : 'transparent'; ?>; color: <?php echo $data['selectedCategory'] == null ? 'white' : 'inherit'; ?>;">
                        All Categories
                    </a>
                    <?php foreach($data['categories'] as $cat) : ?>
                        <a href="<?php echo URLROOT; ?>/buyer/shop?category=<?php echo urlencode($cat->category); ?>" class="list-group-item list-group-item-action <?php echo $data['selectedCategory'] == $cat->category ? 'active' : ''; ?>" style="background-color: <?php echo $data['selectedCategory'] == $cat->category ? 'var(--primary-color)' : 'transparent'; ?>; color: <?php echo $data['selectedCategory'] == $cat->category ? 'white' : 'inherit'; ?>;">
                            <?php echo $cat->category; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                 <div class="mt-4">
                    <a href="<?php echo URLROOT; ?>/buyer/dashboard" class="btn btn-secondary btn-block">Back to Dashboard</a>
                </div>
            </div>
        </div>

        <!-- Product Grid (Now Single Card Table) -->
        <div class="col-md-9">
             <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Shop Products</h2>
                <?php flash('cart_error'); ?>
            </div>

            <div class="glass-card p-4">
                <?php if(empty($data['products'])): ?>
                     <div class="text-center p-5">
                        <h3>No products found.</h3>
                        <a href="<?php echo URLROOT; ?>/buyer/shop" class="btn btn-primary mt-3">Reset Search</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 80px;">Image</th>
                                    <th scope="col">Product Information</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Stock</th>
                                    <th scope="col" style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['products'] as $product) : ?>
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                                                🛍️
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <h5 class="mb-1">
                                                <a href="<?php echo URLROOT; ?>/products/show/<?php echo $product->id; ?>" class="text-primary text-decoration-none">
                                                    <?php echo $product->name; ?>
                                                </a>
                                            </h5>
                                            <span class="badge badge-pill badge-light border"><?php echo $product->category; ?></span>
                                            <small class="text-muted d-block mt-1"><?php echo substr($product->description, 0, 50); ?>...</small>
                                        </td>
                                        <td class="align-middle font-weight-bold">
                                            ₹<?php echo $product->price; ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php if($product->quantity > 0): ?>
                                                <span class="text-success">In Stock (<?php echo $product->quantity; ?>)</span>
                                            <?php else: ?>
                                                <span class="text-danger">Out of Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php if($product->quantity > 0): ?>
                                                <form action="<?php echo URLROOT; ?>/buyer/addToCart" method="post" class="d-flex align-items-center">
                                                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $product->quantity; ?>" class="form-control form-control-sm mr-2" style="width: 60px;">
                                                    <button type="submit" class="btn btn-sm btn-primary">Add</button>
                                                </form>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary" disabled>No Stock</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
