const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

if (togglePassword) {
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
    });

}

$(document).ready(function() {
    // Toggle the floating cart when the button is clicked
    $('#toggleCart').click(function() {
        $('#floatingCart').slideToggle();
    });

    // Example: Add items to the cart dynamically
    $('#addToCartBtn').click(function() {
        addToCart('Product Name', 10.00); // Example product name and price
    });

    // Example: Checkout button functionality
    $('#checkoutBtn').click(function() {
        alert('Redirect to checkout page...');
    });
});

// Function to add items to the cart
function addToCart(productName, price) {
    $('#floatingCart ul').append(`<li class="list-group-item">${productName} - $${price.toFixed(2)}</li>`);
    updateTotal(price);
}

// Function to update the total price
function updateTotal(price) {
    var currentTotal = parseFloat($('#cartTotal').text());
    var newTotal = currentTotal + price;
    $('#cartTotal').text(newTotal.toFixed(2));
}