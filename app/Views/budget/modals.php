<!-- Add Budget Modal -->
<div class="modal fade" id="addBudgetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>Add New Budget
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBudgetForm" action="<?= site_url('budget/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Budget Amount</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-birr">Br</span>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   step="0.01" min="0.01" required placeholder="Enter amount">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="month_year" class="form-label">Month & Year</label>
                        <input type="month" class="form-control" id="month_year" name="month_year" 
                               value="<?= $current_year . '-' . $current_month ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3" placeholder="Add any notes about this budget"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="addBudgetBtn">
                        <i class="fas fa-plus-circle me-2"></i>Add Budget
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Budget Modal -->
<div class="modal fade" id="editBudgetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2 text-primary"></i>Edit Budget
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBudgetForm" action="" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_amount" class="form-label">Budget Amount</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-birr">Br</span>
                            <input type="number" class="form-control" id="edit_amount" name="amount" 
                                   step="0.01" min="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="edit_description" name="description" 
                                  rows="3" placeholder="Add any notes about this budget"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="editBudgetBtn">
                        <i class="fas fa-save me-2"></i>Update Budget
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Suggestions Modal -->
<div class="modal fade" id="suggestionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-lightbulb me-2 text-warning"></i>Budget Suggestions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($budget_suggestions)): ?>
                    <?php foreach($budget_suggestions as $suggestion): ?>
                    <div class="card suggestion-card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?= $suggestion['category_name'] ?></h6>
                                    <small class="text-muted"><?= $suggestion['reason'] ?></small>
                                </div>
                                <div class="text-end">
                                    <h5 class="text-success mb-1 currency-birr">Br <?= number_format($suggestion['suggested_amount'], 2) ?></h5>
                                    <button class="btn btn-sm btn-success use-suggestion" 
                                            data-category-id="<?= $suggestion['category_id'] ?>"
                                            data-amount="<?= $suggestion['suggested_amount'] ?>">
                                        Use This
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">Great! You have budgets set for all your categories.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Duplicate Budget Modal -->
<div class="modal fade" id="duplicateBudgetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-copy me-2 text-primary"></i>Duplicate Budgets
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="duplicateBudgetForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="from_month" class="form-label">Copy From Month</label>
                        <input type="month" class="form-control" id="from_month" name="from_month" 
                               value="<?= date('Y-m', strtotime('-1 month')) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="to_month" class="form-label">Paste To Month</label>
                        <input type="month" class="form-control" id="to_month" name="to_month" 
                               value="<?= $current_year . '-' . $current_month ?>" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This will copy all budgets from the source month to the target month.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="duplicateBudgetBtn">
                        <i class="fas fa-copy me-2"></i>Duplicate Budgets
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>