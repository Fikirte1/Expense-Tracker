<!-- File: app/Views/expenses/create.php -->
<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Add New Expense</h2>
                
                <!-- Display validation errors -->
                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session('errors') as $error): ?>
                            <p><?= esc($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Display success message -->
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success">
                        <?= session('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('expenses/store') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Expense Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?= set_value('title', '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                               value="<?= set_value('amount', '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="expense_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="expense_date" name="expense_date" 
                               value="<?= set_value('expense_date', date('Y-m-d')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                    <?= set_select('category_id', $category['id']) ?>>
                                    <?= esc($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Replace the receipt text input with file input -->
<div class="mb-4">
    <label for="receipt" class="form-label fw-bold">
        <i class="fas fa-receipt me-2 text-primary"></i>Receipt (Optional)
    </label>
    <input type="file" 
           class="form-control" 
           id="receipt" 
           name="receipt" 
           accept=".jpg,.jpeg,.png,.pdf,.gif">
    <div class="form-text">
        Supported formats: JPG, JPEG, PNG, PDF, GIF (Max: 2MB)
    </div>
    <!-- File preview -->
    <div id="filePreview" class="mt-2" style="display: none;">
        <img id="imagePreview" class="img-thumbnail" style="max-height: 200px; display: none;">
        <div id="pdfPreview" class="alert alert-info" style="display: none;">
            <i class="fas fa-file-pdf me-2"></i>PDF file selected
        </div>
    </div>
</div>
                    <button type="submit" class="btn btn-primary">Add Expense</button>
                    <a href="<?= site_url('expenses') ?>" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>