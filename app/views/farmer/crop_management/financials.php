<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/farmer/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/crop_management">Crop Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Financials</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h1>Financial Overview</h1>
            <button class="btn btn-danger" data-toggle="modal" data-target="#addExpenseModal"><i class="fa fa-minus-circle"></i> Add Expense</button>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3 shadow-sm">
             <div class="card-header">Total Revenue</div>
            <div class="card-body">
                <h4 class="card-title">INR <?php echo number_format($data['total_revenue'], 2); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3 shadow-sm">
             <div class="card-header">Total Expenses</div>
             <div class="card-body">
                <h4 class="card-title">INR <?php echo number_format($data['total_expenses'], 2); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white <?php echo $data['profit'] >= 0 ? 'bg-primary' : 'bg-secondary'; ?> mb-3 shadow-sm">
             <div class="card-header">Net Profit</div>
             <div class="card-body">
                <h4 class="card-title">INR <?php echo number_format($data['profit'], 2); ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">Recent Expenses</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Cycle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['expenses'])): ?>
                            <tr><td colspan="5" class="text-center p-3">No expenses recorded.</td></tr>
                        <?php else: ?>
                            <?php foreach ($data['expenses'] as $exp): ?>
                                <tr>
                                    <td><?php echo $exp->date; ?></td>
                                    <td><?php echo $exp->category; ?></td>
                                    <td><?php echo $exp->description; ?></td>
                                    <td class="text-danger">- <?php echo number_format($exp->amount, 2); ?></td>
                                    <td><?php echo $exp->cycle_season ? $exp->cycle_season : 'General'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/crop_management/add_expense" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Record Expense</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="Seeds">Seeds</option>
                            <option value="Fertilizers">Fertilizers</option>
                            <option value="Labor">Labor</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Amount</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
                    <div class="form-group"><label>Date</label><input type="date" name="date" class="form-control" required></div>
                    <div class="form-group"><label>Description</label><input type="text" name="description" class="form-control"></div>
                    <div class="form-group">
                        <label>Related Crop Cycle (Optional)</label>
                        <select name="related_cycle_id" class="form-control">
                            <option value="">-- General Farm Expense --</option>
                            <?php foreach ($data['cycles'] as $cycle): ?>
                                <option value="<?php echo $cycle->id; ?>"><?php echo $cycle->crop_name . ' (' . $cycle->season . ')'; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-danger">Record Expense</button></div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
