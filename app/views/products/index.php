<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/admin/dashboard" class="btn btn-primary">
            < Back to Dashboard</a>
                <br><br>
                <h1>Products</h1>
                <a href="<?php echo URLROOT; ?>/products/add" class="btn btn-primary" style="float: right;">Add
                    Product</a>
                <br><br>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['products'] as $product): ?>
                            <tr>
                                <td>
                                    <?php echo $product->name; ?>
                                </td>
                                <td>
                                    <?php echo $product->price; ?>
                                </td>
                                <td>
                                    <?php echo $product->quantity; ?>
                                </td>
                                <td>
                                    <?php echo $product->description; ?>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/products/edit/<?php echo $product->id; ?>"
                                        class="btn btn-primary">Edit</a>
                                    <form action="<?php echo URLROOT; ?>/products/delete/<?php echo $product->id; ?>"
                                        method="post" style="display:inline;">
                                        <input type="submit" value="Delete" class="btn btn-primary"
                                            style="background-color: #d32f2f;">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>