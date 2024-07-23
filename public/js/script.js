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

    // Handle clicks on dynamically added quantity toggler buttons
    $(document).on('click', '.quantity-toggler.on-home', function() {
        console.log('quantity click');
        var input = $(this).siblings('input');
        var value = parseInt(input.val());
        if ($(this).text() === '+') {
            input.val(value + 1);
        } else {
            input.val(Math.max(value - 1, 1));
        }
    });

    // Function to add items to the cart
    function addToCart(bakedGoodId, quantity) {
        $.ajax({
            url: '/api/cart/add',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id_baked_good: bakedGoodId,
                id_user: window.auth.userId,
                qty: quantity
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Status',
                    text: 'Baked good was added to yout cart successfully.',
                    timer: 3000,
                    confirmButtonText: 'OK'
                });

                console.log('Item added to cart:', response);
                $(`button[data-product-id="${bakedGoodId}"]`).closest('.d-flex').find('.quantity-input').val(1);

                if (!response.isUpdated) {
                    // Update the cart item number
                    let cartItemNumberValue = parseInt($('.dot-label').first().text().trim(), 10);
                    if (isNaN(cartItemNumberValue)) {
                        cartItemNumberValue = 0; // Fallback to 0 if parsing fails
                    }

                    let newCartItemNumberValue = cartItemNumberValue + 1;
                    if (newCartItemNumberValue < 0) {
                        newCartItemNumberValue = 0; // Ensure the value doesn't go below 0
                    }

                    $('.dot-label').each(function() {
                        $(this).text(newCartItemNumberValue);
                    });
                }
                updateCartItems(window.auth.userId);
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Status',
                    text: 'Baked good was added to your cart successfully.',
                    timer: 3000,
                    confirmButtonText: 'OK'
                });
                console.error('Error:', xhr.responseText);
            }
        });
    }

    // Attach addToCart function to button click event
    $(document).on('click', '.add-to-cart-btn', function() {
        const bakedGoodId = $(this).data('product-id');
        const quantity = $(this).closest('.d-flex').find('.quantity-input').val();
        console.log(bakedGoodId, quantity);

        // Call the function to add the item to the cart
        addToCart(bakedGoodId, quantity);
    });

    // Function to remove items from the cart
    function removeFromCart(idCart) {
        $.ajax({
            url: '/api/cart/remove',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: idCart
            },
            success: function(response) {
                updateCartItems(window.auth.userId);
                console.log('Cart Item removed successfully');
                // Update the cart item number
                let cartItemNumberValue = parseInt($('.dot-label').first().text().trim(), 10);
                if (isNaN(cartItemNumberValue)) {
                    cartItemNumberValue = 0; // Fallback to 0 if parsing fails
                }

                let newCartItemNumberValue = cartItemNumberValue - 1;
                if (newCartItemNumberValue < 0) {
                    newCartItemNumberValue = 0; // Ensure the value doesn't go below 0
                }

                $('.dot-label').each(function() {
                    $(this).text(newCartItemNumberValue);
                });
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
            }
        });
    }

    // Attach removeFromCart function to button click event
    $(document).on('click', '.delete-cart-item', function(e) {
        e.preventDefault();
        const cartId = $(this).data('id');
        console.log(cartId);
        removeFromCart(cartId);
    });

    $(document).ready(function() {
        // Function to update cart quantity on the server
        function updateCartQuantity(idCart, newQuantity) {
            // Validate newQuantity to ensure it's a number and greater than 0
            newQuantity = parseInt(newQuantity);
            if (isNaN(newQuantity) || newQuantity <= 0) {
                console.error('Invalid quantity:', newQuantity);
                return;
            }

            $.ajax({
                url: '/api/cart/update',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: idCart,
                    qty: newQuantity
                },
                success: function(response) {
                    console.log('Cart updated:', response);
                    // Update the UI
                    recalculateTotals();
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        }

        // Function to recalculate totals
        function recalculateTotals() {
            let grandTotal = 0;

            // Iterate through each cart item
            $('.cartItem').each(function() {
                const price = parseFloat($(this).find('.price').text());
                const quantity = parseInt($(this).find('.quantity-input').val());

                // Validate quantity and price
                if (isNaN(price) || isNaN(quantity) || quantity <= 0) {
                    return;
                }

                const total = price * quantity;

                // Update the total for the specific cart item
                $(this).find('.total').text(`P${total.toFixed(2)}`);

                // Add to grand total
                grandTotal += total;
            });

            // Update the grand total
            $('.grand-total').text(grandTotal.toFixed(2));
        }

        // Ensure event handler is attached only once
        $(document).off('click', '.quantity-toggler.on-cart').on('click', '.quantity-toggler', function() {
            const $this = $(this);
            const $quantityInput = $this.siblings('.quantity-input');
            let quantity = parseInt($quantityInput.val());
            const idCart = $this.closest('.cartItem').find('input[name="idCart"]').val();

            if (isNaN(quantity)) {
                quantity = 1; // Default to 1 if the current quantity is not a number
            }

            if ($this.hasClass('minus')) {
                if (quantity > 1) {
                    quantity--;
                    $quantityInput.val(quantity);
                    console.log($quantityInput.val());
                    updateCartQuantity(idCart, quantity);
                }
            } else if ($this.hasClass('add')) {
                quantity++;
                $quantityInput.val(quantity);
                console.log($quantityInput.val());
                updateCartQuantity(idCart, quantity);
            }
        });

        // Initial call to recalculate totals on page load
        recalculateTotals();
    });


    // Attach updateCartQuantity function to quantity input change event
    $(document).on('change', '.quantity-input', function() {
        const bakedGoodId = $(this).closest('.product-card').find('.add-to-cart-btn').data('product-id');
        const newQuantity = $(this).val();
        updateCartQuantity(bakedGoodId, newQuantity);
    });

    // Function to fetch and update cart items
    function updateCartItems(idUser) {
        $.ajax({
            url: '/api/cart/items',
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id_user: idUser,
            },
            success: function(response) {
                const cartItemsContainer = $('#cart-items-container');
                const cartItems = response.cartItems;
                const grandTotal = response.grandTotal;

                // Clear the cart items container
                cartItemsContainer.html('');

                // Find the latest ID
                let latestId = 0;
                if (cartItems.length > 0) {
                    latestId = Math.max(...cartItems.map(cartItem => cartItem.id));
                }

                // Create the updated cart items HTML
                let cartItemsHtml = '<div id="cartItems"><h2>My Cart</h2>';

                cartItems.forEach(cartItem => {
                    const bakedGood = cartItem.baked_good;
                    const total = cartItem.qty * bakedGood.price;

                    cartItemsHtml += `
                        <div class='cartItem d-flex flex-row align-items-center p-1 mb-2' style="position: relative; background: rgb(141, 244, 223);">
                            <input type='hidden' name='idCart' value="${cartItem.id}" class='bakedGoodId'>
                            <div class="col-3 px-1">
                                <img src="${bakedGood.images.find(image => image.is_thumbnail)?.image_path ?? bakedGood.images[0]?.image_path ?? 'uploaded_files/default-profile.png'}" class="img-thumbnail" style='height: 70px; width: 70px;'>
                            </div>
                            <div class="col-3 px-1 d-flex flex-column align-items-center justify-content-center">
                                <p class='mb-0'>${bakedGood.name}</p>
                                <p class='mb-0'>P<span class='price'>${bakedGood.price}</span></p>
                            </div>
                            <form action="" method="POST" class="d-flex align-items-center px-1 text-center flex-1">
                                <div class="quantity-toggler on-cart btn minus">-</div>
                                <input type="number" name='qty' class="quantity-input border-0 m-0 d-flex align-items-center justify-content-center" style="width:30px; background: transparent;" min=1 value="${bakedGood.is_available ? cartItem.qty : 0}" ${bakedGood.is_available ? '' : 'readonly'}>
                                <div class="quantity-toggler on-cart btn add">+</div>
                            </form>
                            <div class="d-flex align-items-center col-2 px-1 text-start">
                                <p class="mb-0 w-100 text-start total">P${total}</p>
                            </div>
                            <div class="d-flex align-items-center col-1 px-1 text-center" style='display: flex; align-items: center; justify-content: center; position: absolute; width: 40px; height: 40px; border-radius: 50%; right: -20px; top: -20px; z-index: 100'>
                                <button name="submit" class="text-center btn btn-danger mb-0 p-1 text-start delete-cart-item" data-id="${cartItem.id}" style="width: 30px; height: 30px; border-radius: 50%; padding: 0; margin: 0;">x</button>
                            </div>
                        </div>
                    `;
                });

                cartItemsHtml += `
                    </div>
                    <div>
                        <div class="cart-total text-end">
                            Total: P<span class='grand-total'>${grandTotal}</span>
                        </div>
                        ${cartItems.length ? `<form action="" method="GET"><button type="submit" class="btn btn-success checkout-btn" style="background:#ead660; color: black">Checkout</button></form>` : `<a href="{{ route('welcome') }}" class="btn btn-success checkout-btn" style="background:#ead660; color: black">Go to Baked Goods</a>`}
                    </div>
                `;

                // Append the updated HTML to the cart items container
                cartItemsContainer.append(cartItemsHtml);

                // Display the latest ID
                console.log('Latest Cart Item ID:', latestId);
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
            }
        });
    }

    // Attach viewCart function to button click event
    $('#viewCartBtn').click(function() {
        viewCart();
    });

    $(document).on('change', '.quantity-input', function() {
        const bakedGoodId = $(this).closest('.product-card').find('.add-to-cart-btn').data('product-id');
        const newQuantity = $(this).val();
        updateCartQuantity(bakedGoodId, newQuantity);
    });

    // Load more baked goods on scroll
    let loading = false; // Flag to prevent multiple AJAX calls
    let page = 1; // Start with the first page

    function loadMoreBakedGoods() {
        if (loading) return; // Prevent multiple AJAX calls

        loading = true;
        $('#loader').show(); // Show the loader

        $.ajax({
            url: 'api/bakedgoods/paginate?page=' + page,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                setTimeout(function() {
                    $('#loader').hide();
                    loading = false;
                    page++; // Increment the page number

                    // Append new baked goods to the container
                    const bakedGoodsContainer = $('#baked-goods-container');
                    response.data.forEach(function(bakedGood) {
                        const sortedImages = bakedGood.images.sort((a, b) => b.is_thumbnail - a.is_thumbnail);
                        const defaultImagePath = 'uploaded_files/default-profile.png'; // Define the path for the default image

                        const html = `
                            <form class="col-3" action='' method='POST'>
                                <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                                <div class="product-card" style="position: relative;">
                                    <a href="#" class="baked-good-link" data-id="${bakedGood.id}">
                                        <div class="product-image-carousel">
                                            ${
                                                sortedImages.length
                                                ? sortedImages.map(img => `
                                                    <div><img src="${img.image_path}" alt="${bakedGood.name}" class="w-100" style="min-height: 300px; max-height: 300px; object-fit: cover;"></div>
                                                `).join('')
                                                : `<div><img src="${defaultImagePath}" alt="${bakedGood.name}" class="w-100" style="min-height: 300px; max-height: 300px; object-fit: cover;"></div>`
                                            }
                                        </div>
                                    </a>
                                    <h5 class="mt-2">${bakedGood.name}</h5>
                                    <p class='mb-1'><span class='fw-semibold'>Price:</span> P${bakedGood.price}</p>
                                    <p class='mb-1'><span class='fw-semibold'>Weight:</span> ${bakedGood.weight_gram} gram</p>
                                    <p class="mb-1 ${bakedGood.is_available ? 'bg-success' : 'bg-danger'}" style="position: absolute; top: 0; left: 0; padding: 5px 10px; color: white; border-bottom-right-radius: 10px; border-top-left-radius: 5px;">${bakedGood.is_available ? "Available" : "Not Available"}</p>
                                    <input type='hidden' name='id' value='${bakedGood.id}'>
                                    <input type='hidden' name='name' value='${bakedGood.name}'>
                                    <input type='hidden' name='price' value='${bakedGood.price}'>

                                    ${authCheck() ? renderAddCartButton(bakedGood) : ''}
                                </div>
                            </form>
                        `;
                        bakedGoodsContainer.append(html); // Add the new baked goods to the container
                    });

                    // Initialize the carousel
                    $('.product-image-carousel').slick({
                        dots: true,
                        infinite: true,
                        speed: 300,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: true
                    });

                    // Show the #end element if there's no more pages
                    if (!response.next_page_url) {
                        $('#end').show(); // Show the end indicator
                        $(window).off('scroll', onScroll); // Remove the scroll event listener
                    }
                }, 1000); // Wait for 1 seconds before appending products
            },
            error: function() {
                $('#loader').hide(); // Hide the loader on error
                $('#end').show(); // Show the end indicator on error
                loading = false; // Reset loading flag
            }
        });
    }
    loadMoreBakedGoods(); // Load more baked goods

    // Event listener for baked good links
    $(document).on('click', '.baked-good-link', function(e) {
        e.preventDefault();
        var bakedGoodId = $(this).data('id');

        $.ajax({
            url: 'api/baked_goods/' + bakedGoodId,
            type: 'GET',
            dataType: 'json',
            success: function(bakedGood) {
                var imagePath = 'uploaded_files/default-profile.png'; // Default image

                if (bakedGood.images.length) {
                    // Find the image with is_thumbnail set to true
                    var thumbnailImage = bakedGood.images.find(image => image.is_thumbnail);

                    if (thumbnailImage) {
                        imagePath = thumbnailImage.image_path; // Set to the thumbnail image if found
                    } else {
                        imagePath = bakedGood.images[0].image_path; // Set to the first image if no thumbnail is found
                    }
                }
                var modalContent = `
                    <div class=''>
                        <div class='left'>
                            <img src="${imagePath}" alt="${bakedGood.name}" class="img-fluid mb-3 w-100" style="min-height: 270px; object-fit: cover;">
                            <h5>${bakedGood.name}</h5>
                            <hr>
                            <p><strong>Price:</strong> P${bakedGood.price}</p>
                            <p><strong>Weight:</strong> ${bakedGood.weight_gram} gram</p>
                            <strong>Description:</strong>
                            <p> ${bakedGood.description}</p>
                            <p class="mb-1 ${bakedGood.is_available ? 'bg-success' : 'bg-danger'}" style="position: absolute; top: 16px; right: 16px; padding: 5px 10px; color: white; border-bottom-left-radius: 10px; border-top-right-radius: 5px;">${bakedGood.is_available ? "Available" : "Not Available"}</p>
                            <p class='fw-bold'>Ingredients</p>
                            <div class="row">
                                <ol class="p-2">
                                    ${bakedGood.ingredients.map(ingredient => `
                                        <li class='mx-4 d-flex align-items-center gap-3'>
                                        <img src='${ingredient.image_path ? ingredient.image_path : 'uploaded_files/default-product.png'}' width=40px height=40px>
                                            ${ingredient.pivot.qty} ${ingredient.unit} ${ingredient.name}
                                        </li>
                                    `).join('')}
                                </ol>
                            </div>
                            ${authCheck() ? renderAddCartButton(bakedGood) : ''}
                        </div>
                    </div>
                `;
                $('#bakedGoodDetails').html(modalContent);
                $('#bakedGoodModal').modal('show');
            }
        });
    });

    function onScroll() {
        // Check if user has scrolled close to the bottom
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            loadMoreBakedGoods(); // Load more baked goods
        }
    }

    $(window).on('scroll', onScroll); // Attach scroll event listener

    function renderAddCartButton(bakedGood) {
        return `
            <div class="d-flex flex-column align-items-center gap-2 ">
                <div class="counter d-flex flex-row">
                    <div class="btn btn-primary quantity-toggler on-home" ${bakedGood.is_available ? "" : "disabled"}>-</div>
                    <input type="number" name='qty' class="form-control border-0 quantity-input" min=1 value="${bakedGood.is_available ? 1 : 0}" ${bakedGood.is_available ? "" : "readonly"}>
                    <div class="btn btn-primary quantity-toggler on-home" ${bakedGood.is_available ? "" : "disabled"}>+</div>
                </div>
                <button type='button' class="btn add-to-cart-btn ${bakedGood.is_available ? "btn-success" : "btn-danger"} mt-2" style="width: 100%" ${bakedGood.is_available ? "" : "disabled"} data-product-id="${bakedGood.id}" data-product-name="${bakedGood.name}" data-product-price="${bakedGood.price}">${bakedGood.is_available ? "Add to Cart" : "Unavailable"}</button>
            </div>
        `;
    }

    function authCheck() {
        if (window.auth.isAuthenticated) {
            if (window.auth.isAdmin) {
                return false;
            }
        }
        return window.auth.isAuthenticated;
    }

    $(document).ready(function() {
        $('#itemSearch').on('input', function() {
            var query = $(this).val();

            loading = false; // Flag to prevent multiple AJAX calls
            page = 1;

            if (query.length < 1) {
                $('#baked-goods-container').empty(); // Clear the container if query is too short
                $('.suggestions-box').remove();
                loadMoreBakedGoods();
                return;
            }

            $.ajax({
                url: '/api/search/bakedgoods',
                method: 'GET',
                data: {
                    q: query
                },
                success: function(response) {
                    console.log('Search response:', response); // Log response

                    var suggestions = response.results;
                    var html = '';
                    $('.no-result-found-label').remove();

                    // Clear previous search results
                    $('#baked-goods-container').empty();

                    if (suggestions && suggestions.length > 0) {
                        suggestions.forEach(function(bakedGood) {
                            html += `<div class="suggestion">${bakedGood.name}</div>`; // Fixed HTML tags
                        });
                        $('.suggestions-box').remove(); // Remove previous suggestions
                        $('.search-bar').append('<div class="suggestions-box">' + html + '</div>');
                        html = '';

                        suggestions.forEach(function(bakedGood) {
                            console.log(bakedGood);
                            const imagePath = Array.isArray(bakedGood.images) && bakedGood.images.length
                            ? bakedGood.images.find(img => img.is_thumbnail)?.image_path || bakedGood.images[0].image_path
                            : 'uploaded_files/default-profile.png';
                            html += `
                                <form class="col-3" action='' method='POST'>
                                    <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                                    <div class="product-card" style="position: relative;">
                                        <a href="#" class="baked-good-link" data-id="${bakedGood.id}">
                                            <img src="${imagePath}" alt="${bakedGood.name}" class="product-image w-100" style="min-height: 300px; max-height: 300px; object-fit: cover;">
                                        </a>
                                        <h5 class="mt-2">${bakedGood.name}</h5>
                                        <p class='mb-1'><span class='fw-semibold'>Price:</span> P${bakedGood.price}</p>
                                        <p class='mb-1'><span class='fw-semibold'>Weight:</span> ${bakedGood.weight_gram} gram</p>
                                        <p class="mb-1 ${bakedGood.is_available ? 'bg-success' : 'bg-danger'}" style="position: absolute; top: 0; left: 0; padding: 5px 10px; color: white; border-bottom-right-radius: 10px; border-top-left-radius: 5px;">${bakedGood.is_available ? "Available" : "Not Available"}</p>
                                        <input type='hidden' name='id' value='${bakedGood.id}'>
                                        <input type='hidden' name='name' value='${bakedGood.name}'>
                                        <input type='hidden' name='price' value='${bakedGood.price}'>

                                        ${authCheck() ? renderAddCartButton(bakedGood) : ''}
                                    </div>
                                </form>
                            `;
                        });
                        $('#baked-goods-container').html(html); // Add new baked goods to the container
                    } else {
                        $('.suggestions-box').remove(); // No results
                        $('#baked-goods-container').html('<h5 class="no-result-found-label mt-3 p-2 text-center" style="border-radius:5%; background-color: lightyellow;">No results found</h5>'); // Show no results message
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        });

        // Handle click on suggestion
        $(document).on('click', '.suggestion', function() {
            var bakedGoodName = $(this).text();
            $('#itemSearch').val(bakedGoodName);
            $('.suggestions-box').remove();
        });

        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-bar').length) {
                $('.suggestions-box').remove();
            }
        });
    });
});


