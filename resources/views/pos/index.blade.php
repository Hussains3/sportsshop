<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Point of Sale') }}
            </h2>
            <a href="{{ route('pos.sales') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Sales History
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Product Selection Panel -->
                        <div class="lg:col-span-2">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Product Selection</h3>

                                <!-- Search Bar -->
                                <div class="mb-4">
                                    <div class="relative">
                                        <input type="text" id="productSearch" placeholder="Search products by name or SKU..."
                                               class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <button id="clearSearch" class="absolute right-2 top-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300" style="display: none;">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Category Filter -->
                                <div class="mb-4">
                                    <select id="categoryFilter" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Products Grid -->
                                <div id="productsGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-96 overflow-y-auto">
                                    @foreach($products as $product)
                                        <div class="product-card bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer hover:shadow-md transition-shadow"
                                             data-product-id="{{ $product->id }}"
                                             data-category-id="{{ $product->subcategory->category_id ?? '' }}">
                                            <div class="text-center">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 mx-auto mb-2 object-cover rounded">
                                                @else
                                                    <div class="w-16 h-16 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                                        <span class="text-gray-500 dark:text-gray-400 text-xs">No Image</span>
                                                    </div>
                                                @endif
                                                <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">{{ $product->name }}</h4>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $product->sku }}</p>
                                                <p class="text-xs text-green-600 dark:text-green-400 font-medium">৳{{ number_format($product->min_price ?? 0, 2) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Stock: {{ $product->current_stock }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Cart Panel -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Shopping Cart</h3>

                                <!-- Cart Items -->
                                <div id="cartItems" class="space-y-2 mb-4 max-h-64 overflow-y-auto">
                                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No items in cart</p>
                                </div>

                                <!-- Cart Summary -->
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-4 space-y-2">
                                    <div class="flex justify-between text-gray-900 dark:text-gray-100">
                                        <span>Subtotal:</span>
                                        <span id="subtotal">৳0.00</span>
                                    </div>
                                    <div class="flex justify-between text-gray-900 dark:text-gray-100">
                                        <span>Tax (5%):</span>
                                        <span id="taxAmount">৳0.00</span>
                                    </div>
                                    <div class="flex justify-between text-gray-900 dark:text-gray-100">
                                        <span>Discount:</span>
                                        <span id="discountAmount">৳0.00</span>
                                    </div>
                                    <div class="flex justify-between font-semibold text-lg text-gray-900 dark:text-gray-100">
                                        <span>Total:</span>
                                        <span id="totalAmount">৳0.00</span>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-4">
                                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Quick Actions</h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button id="clearCart" class="bg-red-500 text-white px-3 py-2 rounded text-sm hover:bg-red-600 transition-colors">
                                            Clear Cart
                                        </button>
                                        <button id="setAmountPaid" class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600 transition-colors">
                                            Set Amount
                                        </button>
                                    </div>
                                </div>

                                                                <!-- Payment Section -->
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-4">
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method</label>
                                        <select id="paymentMethod" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="cash">Cash</option>
                                            <option value="card">Card</option>
                                            <option value="mobile_money">Mobile Money</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount Paid</label>
                                        <input type="number" id="amountPaid" step="0.01" min="0"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Change</label>
                                        <input type="text" id="changeAmount" readonly
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                                        <textarea id="saleNotes" rows="2"
                                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                    </div>

                                    <button id="completeSale" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        Complete Sale
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Select Batch</h3>
                <div id="batchOptions" class="space-y-2 mb-4">
                    <!-- Batch options will be populated here -->
                </div>
                <div class="flex justify-end space-x-2">
                    <button id="cancelBatch" class="px-4 py-2 text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <x-slot name="scripts">
        <script>
            let cart = [];
            let selectedProduct = null;

            // Product search functionality
            document.getElementById('productSearch').addEventListener('input', function(e) {
                const query = e.target.value;
                const categoryId = document.getElementById('categoryFilter').value;
                const clearButton = document.getElementById('clearSearch');

                // Show/hide clear button
                if (query.length > 0) {
                    clearButton.style.display = 'block';
                } else {
                    clearButton.style.display = 'none';
                }

                if (query.length > 2 || query.length === 0) {
                    fetch('{{ route("pos.search") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            query: query,
                            category_id: categoryId
                        })
                    })
                    .then(response => response.json())
                    .then(products => {
                        updateProductsGrid(products);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
                }
            });

            // Clear search functionality
            document.getElementById('clearSearch').addEventListener('click', function() {
                document.getElementById('productSearch').value = '';
                document.getElementById('clearSearch').style.display = 'none';
                // Trigger search to show all products
                document.getElementById('productSearch').dispatchEvent(new Event('input'));
            });

            // Category filter
            document.getElementById('categoryFilter').addEventListener('change', function(e) {
                const searchQuery = document.getElementById('productSearch').value;

                // Always trigger search to get filtered results
                document.getElementById('productSearch').dispatchEvent(new Event('input'));
            });

            // Product card click
            document.addEventListener('click', function(e) {
                if (e.target.closest('.product-card')) {
                    const productCard = e.target.closest('.product-card');
                    const productId = productCard.dataset.productId;
                    showBatchSelection(productId);
                }
            });

            // Show batch selection modal
            function showBatchSelection(productId) {
                console.log('Fetching batches for product:', productId);
                fetch('{{ route("pos.batches") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(batches => {
                    console.log('Received batches:', batches);
                    const batchOptions = document.getElementById('batchOptions');
                    batchOptions.innerHTML = '';

                    if (batches.length === 0) {
                        batchOptions.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-4">No batches available for this product</p>';
                    } else {
                        batches.forEach(batch => {
                            const option = document.createElement('div');
                            option.className = 'p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700';
                            option.innerHTML = `
                                <div class="flex justify-between items-center">
                                                                    <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">৳${parseFloat(batch.min_selling_price).toFixed(2)}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Stock: ${batch.current_quantity}</p>
                                </div>
                                    <button type="button" class="add-to-cart-btn px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition-colors"
                                            data-batch='${JSON.stringify(batch)}'>
                                        Add
                                    </button>
                                </div>
                            `;
                            batchOptions.appendChild(option);
                        });
                    }

                    document.getElementById('productModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching batches:', error);
                    alert('Error loading product batches. Please try again.');
                });
            }

            // Add to cart
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-to-cart-btn')) {
                    e.preventDefault();
                    e.stopPropagation();
                    try {
                        const batch = JSON.parse(e.target.dataset.batch);
                        console.log('Adding batch to cart:', batch);
                        addToCart(batch);
                        document.getElementById('productModal').classList.add('hidden');
                    } catch (error) {
                        console.error('Error parsing batch data:', error);
                        alert('Error adding item to cart. Please try again.');
                    }
                }
            });

            // Cancel batch selection
            document.getElementById('cancelBatch').addEventListener('click', function() {
                document.getElementById('productModal').classList.add('hidden');
            });

            // Close modal when clicking outside
            document.getElementById('productModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });

            // Add item to cart
            function addToCart(batch) {
                console.log('Adding to cart:', batch);
                const existingItem = cart.find(item => item.batch_id === batch.id);

                if (existingItem) {
                    if (existingItem.quantity < batch.current_quantity) {
                        existingItem.quantity++;
                        console.log('Increased quantity for existing item');
                    } else {
                        alert('No more stock available for this item!');
                        return;
                    }
                } else {
                    cart.push({
                        product_id: batch.product_id,
                        batch_id: batch.id,
                        product_name: batch.product.name,
                        quantity: 1,
                        unit_price: parseFloat(batch.min_selling_price),
                        discount_amount: 0,
                        max_quantity: batch.current_quantity
                    });
                    console.log('Added new item to cart');
                }

                updateCartDisplay();
            }

                    // Update cart display
            function updateCartDisplay() {
                const cartItems = document.getElementById('cartItems');

                if (cart.length === 0) {
                    cartItems.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-4">No items in cart</p>';
                } else {
                    cartItems.innerHTML = cart.map((item, index) => `
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h4 class="font-medium text-sm text-gray-900 dark:text-gray-100">${item.product_name}</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">৳${item.unit_price.toFixed(2)} x ${item.quantity}</p>
                                </div>
                                <button class="remove-item text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm" data-index="${index}">×</button>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="quantity-btn bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded text-sm hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300" data-index="${index}" data-action="decrease">-</button>
                                <span class="text-sm text-gray-900 dark:text-gray-100">${item.quantity}</span>
                                <button class="quantity-btn bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded text-sm hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300" data-index="${index}" data-action="increase">+</button>
                            </div>
                        </div>
                    `).join('');
                }

                updateCartSummary();
            }

            // Cart item quantity controls
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('quantity-btn')) {
                    const index = parseInt(e.target.dataset.index);
                    const action = e.target.dataset.action;
                    const item = cart[index];

                    if (action === 'increase' && item.quantity < item.max_quantity) {
                        item.quantity++;
                    } else if (action === 'decrease' && item.quantity > 1) {
                        item.quantity--;
                    }

                    updateCartDisplay();
                }

                if (e.target.classList.contains('remove-item')) {
                    const index = parseInt(e.target.dataset.index);
                    cart.splice(index, 1);
                    updateCartDisplay();
                }
            });

            // Update cart summary
            function updateCartSummary() {
                const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
                const taxAmount = subtotal * 0.05; // 5% tax
                const discountAmount = cart.reduce((sum, item) => sum + item.discount_amount, 0);
                const totalAmount = subtotal + taxAmount - discountAmount;

                document.getElementById('subtotal').textContent = `৳${subtotal.toFixed(2)}`;
                document.getElementById('taxAmount').textContent = `৳${taxAmount.toFixed(2)}`;
                document.getElementById('discountAmount').textContent = `৳${discountAmount.toFixed(2)}`;
                document.getElementById('totalAmount').textContent = `৳${totalAmount.toFixed(2)}`;

                // Update change amount
                updateChangeAmount();
            }

            // Update change amount
            function updateChangeAmount() {
                const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
                const totalAmount = parseFloat(document.getElementById('totalAmount').textContent.replace('৳', '')) || 0;
                const changeAmount = amountPaid - totalAmount;

                document.getElementById('changeAmount').value = changeAmount >= 0 ? `৳${changeAmount.toFixed(2)}` : '৳0.00';
            }

            // Amount paid input
            document.getElementById('amountPaid').addEventListener('input', updateChangeAmount);

            // Quick Actions
            document.getElementById('clearCart').addEventListener('click', function() {
                if (confirm('Are you sure you want to clear the cart?')) {
                    cart = [];
                    updateCartDisplay();
                    document.getElementById('amountPaid').value = '';
                }
            });

            document.getElementById('setAmountPaid').addEventListener('click', function() {
                const totalAmount = parseFloat(document.getElementById('totalAmount').textContent.replace('৳', '')) || 0;
                document.getElementById('amountPaid').value = totalAmount.toFixed(2);
                updateChangeAmount();
            });

            // Complete sale
            document.getElementById('completeSale').addEventListener('click', function() {
                if (cart.length === 0) {
                    alert('Please add items to cart first!');
                    return;
                }

                const totalAmount = parseFloat(document.getElementById('totalAmount').textContent.replace('৳', ''));
                const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;

                if (amountPaid < totalAmount) {
                    alert('Amount paid must be equal to or greater than total amount!');
                    return;
                }

                const saleData = {
                    items: cart,
                    subtotal: parseFloat(document.getElementById('subtotal').textContent.replace('৳', '')),
                    tax_amount: parseFloat(document.getElementById('taxAmount').textContent.replace('৳', '')),
                    discount_amount: parseFloat(document.getElementById('discountAmount').textContent.replace('৳', '')),
                    total_amount: totalAmount,
                    amount_paid: amountPaid,
                    payment_method: document.getElementById('paymentMethod').value,
                    notes: document.getElementById('saleNotes').value
                };

                fetch('{{ route("pos.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(saleData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Sale completed successfully!');
                        // Open receipt in new window
                        const receiptUrl = '{{ route("pos.receipt", ":id") }}'.replace(':id', data.sale_id);
                        window.open(receiptUrl, '_blank');
                        // Reset cart
                        cart = [];
                        updateCartDisplay();
                        document.getElementById('amountPaid').value = '';
                        document.getElementById('saleNotes').value = '';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing the sale.');
                });
            });

                    // Update products grid
            function updateProductsGrid(products) {
                const productsGrid = document.getElementById('productsGrid');
                productsGrid.innerHTML = '';

                products.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.className = 'product-card bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer hover:shadow-md transition-shadow';
                    productCard.dataset.productId = product.id;
                    productCard.dataset.categoryId = product.subcategory?.category_id || '';

                    productCard.innerHTML = `
                        <div class="text-center">
                            ${product.image ?
                                `<img src="/storage/${product.image}" alt="${product.name}" class="w-16 h-16 mx-auto mb-2 object-cover rounded">` :
                                `<div class="w-16 h-16 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">No Image</span>
                                </div>`
                            }
                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">${product.name}</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">${product.sku}</p>
                            <p class="text-xs text-green-600 dark:text-green-400 font-medium">৳${(product.min_price || 0).toFixed(2)}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Stock: ${product.current_stock}</p>
                        </div>
                    `;

                    productsGrid.appendChild(productCard);
                });
            }
        </script>
    </x-slot>

</x-app-layout>
