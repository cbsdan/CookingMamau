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


document.addEventListener('DOMContentLoaded', function() {
    // Increment and Decrement Quantity
    var buttons = document.querySelectorAll('.quantity-toggler');
    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            var input = this.parentNode.querySelector('input');
            var value = parseInt(input.value);
            if (this.textContent === '+') {
                input.value = value + 1;
            } else {
                input.value = Math.max(value - 1, 1);
            }
        });
    });

});

document.addEventListener('DOMContentLoaded', function() {
    // Get the open and close button elements
    var openButton = document.querySelector('.open-btn');
    var closeButton = document.querySelector('.close-btn');
    var cart = document.querySelector('.cart');

    // Add event listener for open button click event
    openButton.addEventListener('click', function() {
        // Show the cart
        cart.style.display = 'flex';
        // Hide the open button and show the close button
        openButton.style.display = 'none';
        closeButton.style.display = 'block';
        closeButton.style.right = '370px';
    });

    // Add event listener for close button click event
    closeButton.addEventListener('click', function() {
        // Hide the cart
        cart.style.display = 'none';
        // Hide the close button and show the open button
        closeButton.style.display = 'none';
        openButton.style.display = 'block';
        openButton.style.display = '1rem';
    });
});


// Function to display the checkout modal
function displayCheckoutModal() {
    // Add your logic to display the checkout modal here
    console.log("Displaying checkout modal");
    $('#checkoutModal').modal('show');
}

// Function to place order
function placeOrder() {
    // Add your logic to place the order here
    console.log("Placing order...");
}

const cart = document.querySelector('.cart');
const cartItems = document.querySelectorAll('.cartItem');
const grandTotalEL = cart.querySelector('.grand-total');

let grandTotal = 0;

cartItems.forEach((cartItem)=>{
    const addToggler = cartItem.querySelector('.add');
    const minusToggler = cartItem.querySelector('.minus');
    const quantityInput = cartItem.querySelector('.quantity-input'); // Corrected selector
    const priceEl = cartItem.querySelector('.price'); // Corrected selector
    const totalEl = cartItem.querySelector('.total'); // Corrected selector
    const bakedGoodId = parseInt(cartItem.querySelector('.bakedGoodId').value);

    let quantity = parseInt(quantityInput.value); // Corrected parsing
    let price = parseFloat(priceEl.textContent);
    let total = quantity * price; // Declare total variable

    addToggler.addEventListener('click', ()=>{
        quantityInput.value = ++quantity;
        total = quantity * price;
        totalEl.textContent = total;
        updateGrandTotal(); // Update grand total after each quantity change
        updateCartQuantity(bakedGoodId, quantity);
    });

    minusToggler.addEventListener('click', ()=>{
        if(quantity > 1) { // Ensure quantity doesn't go negative
            quantityInput.value = --quantity; // Corrected decrementing
            total = quantity * price;
            totalEl.textContent = total;
            updateGrandTotal(); // Update grand total after each quantity change
            updateCartQuantity(bakedGoodId, quantity);
        }
    });

    // Function to update grand total
    function updateGrandTotal() {
        grandTotal = 0; // Reset grand total
        cartItems.forEach((cartItem)=>{
            const quantity = parseInt(cartItem.querySelector('.quantity-input').value);
            const price = parseFloat(cartItem.querySelector('.price').textContent);
            grandTotal += quantity * price;
        });
        grandTotalEL.textContent = grandTotal.toFixed(2); // Display grand total with 2 decimal places
    }

    grandTotal += total;
    grandTotalEL.textContent = grandTotal.toFixed(2); // Display grand total with 2 decimal places
});

function updateCartQuantity(bakedGoodId, newQuantity) {
    // Send an AJAX request to update the cart on the server
    fetch(`/cart/${bakedGoodId}/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ id: bakedGoodId, qty : newQuantity })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to update cart');
        }
        return response.json();
    })
    .then(data => {
        // Update the session data or handle any response from the server
        console.log(data.message);
    })
    .catch(error => {
        console.error(error.message);
    });
}

