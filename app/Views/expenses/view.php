<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Details - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .detail-card {
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }
        .amount-display {
            font-size: 2.5rem;
            font-weight: bold;
            color: #dc3545;
        }
        .info-item {
            border-bottom: 1px solid #eee;
            padding: 1rem 0;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .receipt-preview {
            max-width: 300px;
            max-height: 200px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
        }
        .receipt-actions {
            transition: all 0.3s ease;
        }
        .receipt-actions:hover {
            transform: translateY(-2px);
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
                        <a href="<?= site_url('expenses') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Expenses
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('expenses/edit/' . $expense['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="<?= site_url('expenses/delete/' . $expense['id']) ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Are you sure you want to delete this expense? This will also delete the receipt file if exists.')">
                            <i class="fas fa-trash me-2"></i>Delete
                        </a>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= session('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= session('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Expense Details Card -->
                <div class="card detail-card border-0">
                    <div class="card-header bg-primary text-white py-4">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h2 class="card-title mb-1">
                                    <i class="fas fa-receipt me-3"></i><?= esc($expense['title']) ?>
                                </h2>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-tag me-2"></i>
                                    <?= esc($expense['category_name']) ?>
                                </p>
                            </div>
                            <div class="col-4 text-end">
                                <div class="amount-display text-white">
                                    ₹<?= number_format($expense['amount'], 2) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="info-item">
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong><i class="fas fa-calendar me-2 text-primary"></i>Expense Date</strong>
                                </div>
                                <div class="col-sm-8">
                                    <?= date('F j, Y', strtotime($expense['expense_date'])) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong><i class="fas fa-tags me-2 text-primary"></i>Category</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-primary bg-opacity-10 text-primary fs-6">
                                        <?= esc($expense['category_name']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Receipt Section -->
                        <div class="info-item">
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong><i class="fas fa-receipt me-2 text-primary"></i>Receipt</strong>
                                </div>
                                <div class="col-sm-8">
                                    <?php if (!empty($expense['receipt'])): ?>
                                        <div class="receipt-section">
                                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
                                                <!-- Receipt Preview -->
                                                <div class="receipt-preview-container text-center">
                                                    <?php
                                                    $fileExtension = pathinfo($expense['receipt'], PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']);
                                                    $isPDF = strtolower($fileExtension) === 'pdf';
                                                    ?>
                                                    
                                                    <?php if ($isImage): ?>
                                                        <img src="<?= site_url('expenses/view-receipt/' . $expense['id']) ?>" 
                                                             alt="Receipt Preview" 
                                                             class="receipt-preview img-fluid rounded shadow-sm"
                                                             onclick="openModal('<?= site_url('expenses/view-receipt/' . $expense['id']) ?>')"
                                                             style="cursor: zoom-in;">
                                                        <div class="mt-2 small text-muted">Click to enlarge</div>
                                                    <?php elseif ($isPDF): ?>
                                                        <div class="receipt-preview d-flex align-items-center justify-content-center bg-light">
                                                            <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                                        </div>
                                                        <div class="mt-2 small text-muted">PDF Document</div>
                                                    <?php else: ?>
                                                        <div class="receipt-preview d-flex align-items-center justify-content-center bg-light">
                                                            <i class="fas fa-file fa-4x text-secondary"></i>
                                                        </div>
                                                        <div class="mt-2 small text-muted">File Attachment</div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Receipt Actions -->
                                                <div class="receipt-actions">
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <a href="<?= site_url('expenses/download-receipt/' . $expense['id']) ?>" 
                                                           class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                        <a href="<?= site_url('expenses/view-receipt/' . $expense['id']) ?>" 
                                                           class="btn btn-outline-info btn-sm" 
                                                           target="_blank">
                                                            <i class="fas fa-external-link-alt me-1"></i>Open
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-outline-warning btn-sm"
                                                                onclick="copyReceiptLink('<?= site_url('expenses/view-receipt/' . $expense['id']) ?>')">
                                                            <i class="fas fa-link me-1"></i>Copy Link
                                                        </button>
                                                    </div>
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-file me-1"></i>
                                                            File: <?= esc($expense['receipt']) ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-muted">
                                            <i class="fas fa-times-circle me-2"></i>No receipt attached
                                        </div>
                                        <small class="text-muted">
                                            You can add a receipt when editing this expense.
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong><i class="fas fa-file-invoice me-2 text-primary"></i>Notes</strong>
                                </div>
                                <div class="col-sm-8">
                                    <?= !empty($expense['notes']) ? nl2br(esc($expense['notes'])) : '<span class="text-muted">No additional notes</span>' ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong><i class="fas fa-clock me-2 text-primary"></i>Created</strong>
                                </div>
                                <div class="col-sm-8">
                                    <?= date('F j, Y g:i A', strtotime($expense['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong><i class="fas fa-sync me-2 text-primary"></i>Last Updated</strong>
                                </div>
                                <div class="col-sm-8">
                                    <?= date('F j, Y g:i A', strtotime($expense['updated_at'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
                                </h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="<?= site_url('expenses/edit/' . $expense['id']) ?>" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i>Edit Expense
                                    </a>
                                    <?php if (!empty($expense['receipt'])): ?>
                                        <a href="<?= site_url('expenses/download-receipt/' . $expense['id']) ?>" 
                                           class="btn btn-outline-success">
                                            <i class="fas fa-download me-1"></i>Download Receipt
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= site_url('expenses/create') ?>" 
                                       class="btn btn-outline-info">
                                        <i class="fas fa-plus me-1"></i>Add New Expense
                                    </a>
                                    <a href="<?= site_url('expenses') ?>" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-list me-1"></i>View All Expenses
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Receipt Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Receipt" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <a href="#" id="downloadFromModal" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i>Download
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });

        // Image modal functionality
        function openModal(imageSrc) {
            const modalImage = document.getElementById('modalImage');
            const downloadLink = document.getElementById('downloadFromModal');
            
            modalImage.src = imageSrc;
            downloadLink.href = imageSrc.replace('view-receipt', 'download-receipt');
            
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }

        // Copy receipt link to clipboard
        function copyReceiptLink(link) {
            navigator.clipboard.writeText(link).then(function() {
                // Show success feedback
                const originalText = event.target.innerHTML;
                event.target.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
                event.target.classList.remove('btn-outline-warning');
                event.target.classList.add('btn-outline-success');
                
                setTimeout(() => {
                    event.target.innerHTML = originalText;
                    event.target.classList.remove('btn-outline-success');
                    event.target.classList.add('btn-outline-warning');
                }, 2000);
            }).catch(function(err) {
                alert('Failed to copy link: ' + err);
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+E to edit (Cmd+E on Mac)
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                window.location.href = '<?= site_url('expenses/edit/' . $expense['id']) ?>';
            }
            
            // Escape key to go back
            if (e.key === 'Escape') {
                window.location.href = '<?= site_url('expenses') ?>';
            }
        });

        // Print functionality
        function printExpense() {
            const printContent = document.querySelector('.detail-card').outerHTML;
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = `
                <div class="container mt-4">
                    <h2 class="text-center mb-4">Expense Receipt</h2>
                    ${printContent}
                    <div class="text-center mt-4 text-muted">
                        Printed on: ${new Date().toLocaleString()}
                    </div>
                </div>
            `;
            
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }
    </script>
</body>
</html>