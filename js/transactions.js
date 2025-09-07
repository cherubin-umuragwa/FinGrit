// Transaction management functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the transactions page
    if (document.getElementById('transactions-list')) {
        setupTransactionList();
    }
    
    // Check if we're on the add/edit transaction form
    if (document.getElementById('transaction-form')) {
        setupTransactionForm();
    }
});

function setupTransactionList() {
    // Initialize DataTable if available
    if (window.DataTable) {
        new DataTable('#transactions-table', {
            responsive: true,
            order: [[0, 'desc']]
        });
    }
    
    // Setup delete buttons
    document.querySelectorAll('.delete-transaction').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const transactionId = this.dataset.id;
            
            if (confirm('Are you sure you want to delete this transaction?')) {
                deleteTransaction(transactionId);
            }
        });
    });
}

function setupTransactionForm() {
    const form = document.getElementById('transaction-form');
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category');
    
    // Update category options when type changes
    if (typeSelect && categorySelect) {
        typeSelect.addEventListener('change', function() {
            updateCategoryOptions(this.value, categorySelect);
        });
        
        // Initialize categories based on current type
        updateCategoryOptions(typeSelect.value, categorySelect);
    }
    
    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const transactionId = form.dataset.transactionId || null;
            
            saveTransaction(formData, transactionId)
                .then(response => {
                    if (response.success) {
                        window.location.href = 'transactions.php?success=' + encodeURIComponent(response.message);
                    } else {
                        showFormError(response.message);
                    }
                })
                .catch(error => {
                    showFormError(error.message || 'An error occurred');
                });
        });
    }
}

function updateCategoryOptions(type, categorySelect) {
    // This would be better with predefined categories from the server
    const categories = {
        income: ['Salary', 'Freelance', 'Investment', 'Gift', 'Other'],
        expense: ['Groceries', 'Rent', 'Utilities', 'Transportation', 'Entertainment', 'Dining', 'Shopping', 'Healthcare', 'Education', 'Other']
    };
    
    // Clear existing options
    while (categorySelect.options.length > 0) {
        categorySelect.remove(0);
    }
    
    // Add new options
    categories[type].forEach(category => {
        const option = new Option(category, category);
        categorySelect.add(option);
    });
}

function saveTransaction(formData, transactionId) {
    const url = '/api/transactions.php';
    const method = transactionId ? 'PUT' : 'POST';
    
    return fetch(url, {
        method: method,
        body: formData
    })
    .then(response => response.json());
}

function deleteTransaction(transactionId) {
    return fetch('/api/transactions.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: transactionId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function showFormError(message) {
    const errorDiv = document.getElementById('form-error') || document.createElement('div');
    errorDiv.id = 'form-error';
    errorDiv.className = 'alert alert-error';
    errorDiv.textContent = message;
    
    const form = document.getElementById('transaction-form');
    form.prepend(errorDiv);
    
    // Scroll to error
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Export functions for use in other modules
window.FinGritTransactions = {
    saveTransaction,
    deleteTransaction,
    updateCategoryOptions
};