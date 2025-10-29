<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-card {
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold text-primary">
                            <i class="fas fa-edit me-2"></i>Edit Expense
                        </h1>
                        <p class="text-muted mb-0">Update your expense details</p>
                    </div>
                    <a href="<?= site_url('expenses') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Expenses
                    </a>
                </div>

                <!-- Success/Error Messages -->
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= session('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Edit Form -->
                <div class="card form-card border-0">
                    <div class="card-body p-4">
                        <form action="<?= site_url('expenses/update/' . $expense['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="title" class="form-label fw-bold">
                                        <i class="fas fa-heading me-2 text-primary"></i>Expense Title
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="title" 
                                           name="title" 
                                           value="<?= old('title', $expense['title']) ?>" 
                                           placeholder="Enter expense title"
                                           required
                                           autofocus>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="amount" class="form-label fw-bold">
                                        <i class="fas fa-rupee-sign me-2 text-primary"></i>Amount
                                    </label>
                                    <input type="number" 
                                           step="0.01" 
                                           class="form-control form-control-lg" 
                                           id="amount" 
                                           name="amount" 
                                           value="<?= old('amount', $expense['amount']) ?>" 
                                           placeholder="0.00"
                                           required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expense_date" class="form-label fw-bold">
                                        <i class="fas fa-calendar me-2 text-primary"></i>Date
                                    </label>
                                    <input type="date" 
                                           class="form-control form-control-lg" 
                                           id="expense_date" 
                                           name="expense_date" 
                                           value="<?= old('expense_date', $expense['expense_date']) ?>" 
                                           required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label fw-bold">
                                        <i class="fas fa-tags me-2 text-primary"></i>Category
                                    </label>
                                    <select class="form-control form-control-lg" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" 
                                                <?= old('category_id', $expense['category_id']) == $category['id'] ? 'selected' : '' ?>>
                                                <?= esc($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                <!-- Replace the receipt textarea with file input -->
<div class="mb-4">
    <label for="receipt" class="form-label fw-bold">
        <i class="fas fa-receipt me-2 text-primary"></i>Receipt
    </label>
    
    <!-- Show current receipt if exists -->
    <?php if (!empty($expense['receipt'])): ?>
        <div class="mb-2">
            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                <i class="fas fa-paperclip text-primary"></i>
                <span class="small">Current receipt: <?= $expense['receipt'] ?></span>
                <div class="ms-auto">
                    <a href="<?= site_url('expenses/download-receipt/' . $expense['id']) ?>" 
                       class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="fas fa-download me-1"></i>Download
                    </a>
                    <a href="<?= site_url('expenses/view-receipt/' . $expense['id']) ?>" 
                       class="btn btn-sm btn-outline-info" target="_blank">
                        <i class="fas fa-eye me-1"></i>View
                    </a>
                </div>
            </div>
        </div>
        <div class="form-text text-warning">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Uploading a new file will replace the current receipt.
        </div>
    <?php endif; ?>
    
    <input type="file" 
           class="form-control mt-2" 
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

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="<?= site_url('expenses/view/' . $expense['id']) ?>" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Expense
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>