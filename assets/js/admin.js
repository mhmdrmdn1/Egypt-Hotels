// Admin Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tooltips
    initTooltips();
    
    // Initialize all popovers
    initPopovers();
    
    // Initialize form validation
    initFormValidation();
    
    // Initialize image previews
    initImagePreviews();
    
    // Initialize auto-hiding alerts
    initAlerts();
    
    // Initialize responsive tables
    initResponsiveTables();

    // Sidebar Toggle
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebarCollapseTop = document.getElementById('sidebarCollapseTop');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    function toggleSidebar() {
        sidebar.classList.toggle('active');
        content.classList.toggle('active');
        // Store sidebar state in localStorage
        localStorage.setItem('sidebarActive', sidebar.classList.contains('active'));
    }

    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', toggleSidebar);
    }
    if (sidebarCollapseTop) {
        sidebarCollapseTop.addEventListener('click', toggleSidebar);
    }

    // Restore sidebar state
    const sidebarActive = localStorage.getItem('sidebarActive') === 'true';
    if (sidebarActive) {
        sidebar.classList.add('active');
        content.classList.add('active');
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Handle form submissions with loading state
    const forms = document.querySelectorAll('form:not(.search-form)');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('loading');
                // Store original text
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
                
                // Reset button after 10 seconds (failsafe)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('loading');
                    submitBtn.innerHTML = originalText;
                }, 10000);
            }
        });
    });

    // Image preview for file inputs
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const preview = document.getElementById(this.dataset.preview);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm || 'Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });

    // Handle table sorting
    const sortableHeaders = document.querySelectorAll('th[data-sort]');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const table = this.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const column = this.cellIndex;
            const ascending = !this.classList.contains('sort-asc');

            // Update sort indicators
            sortableHeaders.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
            this.classList.add(ascending ? 'sort-asc' : 'sort-desc');

            // Sort rows
            rows.sort((a, b) => {
                const aValue = a.cells[column].textContent;
                const bValue = b.cells[column].textContent;
                return ascending ? 
                    aValue.localeCompare(bValue, undefined, {numeric: true}) :
                    bValue.localeCompare(aValue, undefined, {numeric: true});
            });

            // Reorder rows
            rows.forEach(row => tbody.appendChild(row));
        });
    });

    // Handle bulk actions
    const bulkActionForm = document.getElementById('bulkActionForm');
    if (bulkActionForm) {
        const checkAll = bulkActionForm.querySelector('#checkAll');
        const itemCheckboxes = bulkActionForm.querySelectorAll('.item-checkbox');
        const bulkActionBtn = bulkActionForm.querySelector('#bulkActionBtn');

        // Toggle all checkboxes
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionButton();
            });
        }

        // Update bulk action button state
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionButton);
        });

        function updateBulkActionButton() {
            const checkedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
            if (bulkActionBtn) {
                bulkActionBtn.disabled = checkedCount === 0;
                bulkActionBtn.textContent = `Apply (${checkedCount} selected)`;
            }
        }
    }
});

// Initialize Bootstrap tooltips
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
}

// Initialize Bootstrap popovers
function initPopovers() {
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach(popover => {
        new bootstrap.Popover(popover);
    });
}

// Initialize form validation
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
}

// Initialize image previews
function initImagePreviews() {
    const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');
    imageInputs.forEach(input => {
        const previewId = input.getAttribute('data-preview');
        const preview = document.getElementById(previewId);
        
        if (preview) {
            const defaultSrc = preview.src;
            
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    preview.src = defaultSrc;
                }
            });
        }
    });
}

// Initialize auto-hiding alerts
function initAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });
}

// Initialize responsive tables
function initResponsiveTables() {
    const tables = document.querySelectorAll('table:not(.table-native)');
    tables.forEach(table => {
        if (!table.parentElement.classList.contains('table-responsive')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-responsive';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        }
    });
}

// Utility function to format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Utility function to format date
function formatDate(date) {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Utility function to show loading state
function showLoading(element) {
    element.classList.add('loading');
}

// Utility function to hide loading state
function hideLoading(element) {
    element.classList.remove('loading');
}

// Handle confirmation dialogs
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Handle AJAX form submissions
function submitForm(form, callback) {
    const formData = new FormData(form);
    const submitButton = form.querySelector('[type="submit"]');
    
    if (submitButton) {
        submitButton.disabled = true;
        showLoading(submitButton);
    }
    
    fetch(form.action, {
        method: form.method,
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (callback) {
            callback(data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        if (submitButton) {
            submitButton.disabled = false;
            hideLoading(submitButton);
        }
    });
}

// Handle sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggles = document.querySelectorAll('#sidebarCollapse');
    const sidebar = document.getElementById('sidebar');
    
    sidebarToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    });
}); 
        // Update bulk action button state
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionButton);
        });

        function updateBulkActionButton() {
            const checkedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
            if (bulkActionBtn) {
                bulkActionBtn.disabled = checkedCount === 0;
                bulkActionBtn.textContent = `Apply (${checkedCount} selected)`;
            }
        }

// Initialize Bootstrap tooltips
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
}

// Initialize Bootstrap popovers
function initPopovers() {
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach(popover => {
        new bootstrap.Popover(popover);
    });
}

// Initialize form validation
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
}

// Initialize image previews
function initImagePreviews() {
    const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');
    imageInputs.forEach(input => {
        const previewId = input.getAttribute('data-preview');
        const preview = document.getElementById(previewId);
        
        if (preview) {
            const defaultSrc = preview.src;
            
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    preview.src = defaultSrc;
                }
            });
        }
    });
}

// Initialize auto-hiding alerts
function initAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });
}

// Initialize responsive tables
function initResponsiveTables() {
    const tables = document.querySelectorAll('table:not(.table-native)');
    tables.forEach(table => {
        if (!table.parentElement.classList.contains('table-responsive')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-responsive';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        }
    });
}

// Utility function to format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Utility function to format date
function formatDate(date) {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Utility function to show loading state
function showLoading(element) {
    element.classList.add('loading');
}

// Utility function to hide loading state
function hideLoading(element) {
    element.classList.remove('loading');
}

// Handle confirmation dialogs
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Handle AJAX form submissions
function submitForm(form, callback) {
    const formData = new FormData(form);
    const submitButton = form.querySelector('[type="submit"]');
    
    if (submitButton) {
        submitButton.disabled = true;
        showLoading(submitButton);
    }
    
    fetch(form.action, {
        method: form.method,
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (callback) {
            callback(data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        if (submitButton) {
            submitButton.disabled = false;
            hideLoading(submitButton);
        }
    });
}

// Handle sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggles = document.querySelectorAll('#sidebarCollapse');
    const sidebar = document.getElementById('sidebar');
    
    sidebarToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    });
}); 