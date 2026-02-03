<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/admin/dashboard" class="btn btn-primary">
            < Back to Dashboard</a>
                <br><br>
                <h1>Pesticides</h1>
                <a href="<?php echo URLROOT; ?>/pesticides/add" class="btn btn-primary" style="float: right;">Add
                    Pesticide</a>
                <br><br>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Usage Instructions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['pesticides'] as $pesticide): ?>
                            <tr>
                                <td>
                                    <?php echo $pesticide->name; ?>
                                </td>
                                <td>
                                    <?php echo $pesticide->type; ?>
                                </td>
                                <td>
                                    <?php echo $pesticide->description; ?>
                                </td>
                                <td>
                                    <?php echo $pesticide->usage_instructions; ?>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/pesticides/edit/<?php echo $pesticide->id; ?>"
                                        class="btn btn-primary">Edit</a>
                                    <form action="<?php echo URLROOT; ?>/pesticides/delete/<?php echo $pesticide->id; ?>"
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