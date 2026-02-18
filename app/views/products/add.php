<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <a href="<?php echo URLROOT; ?>/products" class="btn btn-primary">
            < Back</a>
                <h2>Add Product</h2>
                <form action="<?php echo URLROOT; ?>/products/add" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name: <sup>*</sup></label>
                        <input type="text" name="name"
                            class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $data['name']; ?>">
                        <span class="invalid-feedback">
                            <?php echo $data['name_err']; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="price">Price: <sup>*</sup></label>
                        <input type="number" step="0.01" name="price"
                            class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $data['price']; ?>">
                        <span class="invalid-feedback">
                            <?php echo $data['price_err']; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity: <sup>*</sup></label>
                        <input type="number" name="quantity"
                            class="form-control <?php echo (!empty($data['quantity_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $data['quantity']; ?>">
                        <span class="invalid-feedback">
                            <?php echo $data['quantity_err']; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" name="image"
                            class="form-control-file <?php echo (!empty($data['image_err'])) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback">
                            <?php echo $data['image_err']; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" class="form-control"><?php echo $data['description']; ?></textarea>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                </form>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>