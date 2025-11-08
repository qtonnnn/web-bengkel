// Main JavaScript file for Web Bengkel

// Function to validate booking form
function validateBookingForm() {
    const form = document.getElementById('booking-form');
    if (!form) return true;

    const nama = form.nama.value.trim();
    const email = form.email.value.trim();
    const telepon = form.telepon.value.trim();
    const alamat = form.alamat.value.trim();
    const layanan = form.layanan_id.value;
    const tanggal = form.tanggal.value;
    const waktu = form.waktu.value;

    if (!nama) {
        alert('Please enter your name.');
        return false;
    }
    if (!email || !isValidEmail(email)) {
        alert('Please enter a valid email.');
        return false;
    }
    if (!telepon) {
        alert('Please enter your phone number.');
        return false;
    }
    if (!alamat) {
        alert('Please enter your address.');
        return false;
    }
    if (!layanan) {
        alert('Please select a service.');
        return false;
    }
    if (!tanggal) {
        alert('Please select a date.');
        return false;
    }
    if (!waktu) {
        alert('Please select a time.');
        return false;
    }

    return true;
}

// Function to validate email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Function to show success message
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
    successDiv.innerHTML = message;
    const container = document.querySelector('main');
    if (container) {
        container.insertBefore(successDiv, container.firstChild);
        setTimeout(() => {
            successDiv.remove();
        }, 5000);
    }
}

// Function to show error message
function showErrorMessage(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
    errorDiv.innerHTML = message;
    const container = document.querySelector('main');
    if (container) {
        container.insertBefore(errorDiv, container.firstChild);
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }
}

// Function to confirm deletion
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

// Function to toggle mobile menu (if needed)
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

// Function to initialize date picker (if using native date input)
function initializeDatePicker() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        const today = new Date().toISOString().split('T')[0];
        input.setAttribute('min', today);
    });
}

// Function to format currency (client-side)
function formatCurrency(amount) {
    return 'Rp ' + parseFloat(amount).toLocaleString('id-ID', { minimumFractionDigits: 2 });
}

// Initialize functions on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeDatePicker();

    // Attach form validation to booking form
    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            if (!validateBookingForm()) {
                e.preventDefault();
            }
        });
    }

    // Attach confirm delete to delete links
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirmDelete()) {
                e.preventDefault();
            }
        });
    });
});
