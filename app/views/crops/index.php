<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/admin/dashboard" class="btn btn-primary">
            < Back to Dashboard</a>
                <br><br>
                <h1>Crops</h1>
                <a href="<?php echo URLROOT; ?>/crops/add" class="btn btn-primary" style="float: right;">Add Crop</a>
                <br><br>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Variety</th>
                            <th>Season</th>
                            <th>Duration</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['crops'] as $crop): ?>
                            <tr>
                                <td><?php echo $crop->name; ?></td>
                                <td><?php echo $crop->type; ?></td>
                                <td><?php echo $crop->variety; ?></td>
                                <td><?php echo $crop->season; ?></td>
                                <td><?php echo $crop->duration; ?> days</td>
                                <td><?php echo $crop->description; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/crops/edit/<?php echo $crop->id; ?>"
                                        class="btn btn-primary">Edit</a>
                                    <form action="<?php echo URLROOT; ?>/crops/delete/<?php echo $crop->id; ?>"
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