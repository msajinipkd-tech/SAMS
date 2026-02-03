<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/admin/dashboard" class="btn btn-primary">
            < Back to Dashboard</a>
                <br><br>
                <h1>Manage Users</h1>
                <a href="<?php echo URLROOT; ?>/users/add" class="btn btn-primary" style="float: right;">Add User</a>
                <br><br>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['users'] as $user): ?>
                            <tr>
                                <td>
                                    <?php echo $user->username; ?>
                                </td>
                                <td>
                                    <?php echo $user->role; ?>
                                </td>
                                <td>
                                    <?php echo $user->created_at; ?>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/users/edit/<?php echo $user->id; ?>"
                                        class="btn btn-primary">Edit</a>
                                    <?php if ($user->id != $_SESSION['user_id']): ?>
                                        <form action="<?php echo URLROOT; ?>/users/delete/<?php echo $user->id; ?>"
                                            method="post" style="display:inline;">
                                            <input type="submit" value="Delete" class="btn btn-primary"
                                                style="background-color: #d32f2f;">
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>