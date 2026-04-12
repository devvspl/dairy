/**
 * Cart and Wishlist Management with LocalStorage
 * Handles add/remove items, quantity updates, and badge counts
 */

(function() {
  'use strict';

  // Storage keys
  const CART_KEY     = 'dairy_cart';
  const WISHLIST_KEY = 'dairy_wishlist';
  const CART_VERSION = 'dairy_cart_v6';

  // Only clear cart if it has corrupted items (missing variant_id field)
  // Don't clear on every version bump — that would wipe valid cart data on refresh
  (function migrateCart() {
    try {
      const raw = localStorage.getItem(CART_KEY);
      if (!raw) return;
      const items = JSON.parse(raw);
      if (!Array.isArray(items)) { localStorage.removeItem(CART_KEY); return; }
      // If any item is missing variant_id key entirely (not null, but absent), migrate it
      const needsMigration = items.some(i => i && !('variant_id' in i));
      if (needsMigration) {
        const migrated = items.map(i => ({ ...i, variant_id: i.variant_id ?? null }));
        localStorage.setItem(CART_KEY, JSON.stringify(migrated));
      }
    } catch(e) {
      localStorage.removeItem(CART_KEY);
    }
  })();

  // Helper: Get data from localStorage
  function getStorage(key) {
    try {
      const data = localStorage.getItem(key);
      if (!data) return [];
      
      const parsed = JSON.parse(data);
      
      // Validate and clean data
      if (key === CART_KEY) {
        return parsed.filter(item => {
          return item && 
                 item.id && 
                 item.name && 
                 typeof item.price === 'number' && 
                 !isNaN(item.price) &&
                 typeof item.quantity === 'number' &&
                 item.quantity > 0;
        }).map(item => ({
          id:         parseInt(item.id),
          variant_id: item.variant_id ? parseInt(item.variant_id) : null,
          name:       item.name,
          price:      parseFloat(item.price),
          image:      item.image || '',
          slug:       item.slug || '',
          quantity:   parseInt(item.quantity) || 1
        }));
      } else if (key === WISHLIST_KEY) {
        return parsed.filter(item => {
          return item && 
                 item.id && 
                 item.name && 
                 typeof item.price === 'number' && 
                 !isNaN(item.price);
        }).map(item => ({
          id: item.id,
          name: item.name,
          price: parseFloat(item.price),
          image: item.image || '',
          slug: item.slug || ''
        }));
      }
      
      return parsed;
    } catch (e) {
      console.error('Error reading from localStorage:', e);
      return [];
    }
  }

  // Helper: Save data to localStorage
  function setStorage(key, data) {
    try {
      localStorage.setItem(key, JSON.stringify(data));
      return true;
    } catch (e) {
      console.error('Error writing to localStorage:', e);
      return false;
    }
  }

  // Helper: Show toast notification
  function showToast(message, type = 'success') {
    // Remove existing toast
    const existing = document.querySelector('.dairy-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = `dairy-toast dairy-toast-${type}`;
    toast.innerHTML = `
      <i class="fa-solid fa-${type === 'success' ? 'circle-check' : 'circle-xmark'}"></i>
      <span>${message}</span>
    `;
    document.body.appendChild(toast);

    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 10);

    // Auto remove after 3 seconds
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  // ==================== CART FUNCTIONS ====================

  // Get all cart items
  function getCart() {
    return getStorage(CART_KEY);
  }

  // Add item to cart
  function addToCart(product) {
    const cart = getCart();
    
    // Debug: Log what we received
    console.log('addToCart called with:', product);
    
    // Validate product data
    if (!product) {
      console.error('Product is null or undefined');
      showToast('Error: No product data', 'error');
      return false;
    }

    if (!product.id) {
      console.error('Product ID is missing:', product);
      showToast('Error: Product ID missing', 'error');
      return false;
    }

    if (!product.name) {
      console.error('Product name is missing:', product);
      showToast('Error: Product name missing', 'error');
      return false;
    }

    if (product.price === undefined || product.price === null || isNaN(product.price)) {
      console.error('Product price is invalid:', product);
      showToast('Error: Invalid product price', 'error');
      return false;
    }

    const existingIndex = cart.findIndex(item => {
      const sameId      = parseInt(item.id) === parseInt(product.id);
      const itemVid     = item.variant_id ? parseInt(item.variant_id) : null;
      const prodVid     = product.variant_id ? parseInt(product.variant_id) : null;
      const sameVariant = itemVid === prodVid;
      return sameId && sameVariant;
    });

    console.log('Cart lookup:', {
      productId: product.id, variantId: product.variant_id,
      cartItems: cart.map(i => ({id: i.id, variant_id: i.variant_id})),
      foundAt: existingIndex
    });

    if (existingIndex > -1) {
      // Update quantity and price (variant may have changed)
      cart[existingIndex].quantity += product.quantity || 1;
      cart[existingIndex].price = product.price; // always use latest price
      cart[existingIndex].name  = product.name;  // update name (may include variant)
      console.log('Updated existing cart item:', cart[existingIndex]);
    } else {
      // Add new item
      const newItem = {
        id:         parseInt(product.id),
        variant_id: product.variant_id ? parseInt(product.variant_id) : null,
        name:       product.name,
        price:      product.price,
        image:      product.image || '',
        slug:       product.slug || '',
        quantity:   product.quantity || 1
      };
      cart.push(newItem);
      console.log('Added new cart item:', newItem);
    }

    setStorage(CART_KEY, cart);
    updateCartBadge();
    showToast(`${product.name} added to cart!`);
    return true;
  }

  // Remove item from cart (by product id + variant_id)
  function removeFromCart(productId, variantId) {
    let cart = getCart();
    if (variantId !== undefined) {
      cart = cart.filter(item => !(String(item.id) === String(productId) && String(item.variant_id || '') === String(variantId || '')));
    } else {
      cart = cart.filter(item => String(item.id) !== String(productId));
    }
    setStorage(CART_KEY, cart);
    updateCartBadge();
    showToast('Item removed from cart', 'success');
    return true;
  }

  // Update item quantity in cart
  function updateCartQuantity(productId, quantity, variantId) {
    const cart = getCart();
    const item = variantId !== undefined
      ? cart.find(i => String(i.id) === String(productId) && String(i.variant_id || '') === String(variantId || ''))
      : cart.find(i => String(i.id) === String(productId));
    
    if (item) {
      if (quantity <= 0) {
        removeFromCart(productId);
      } else {
        item.quantity = quantity;
        setStorage(CART_KEY, cart);
        updateCartBadge();
      }
    }
  }

  // Clear entire cart
  function clearCart() {
    setStorage(CART_KEY, []);
    updateCartBadge();
    showToast('Cart cleared', 'success');
  }

  // Get cart total
  function getCartTotal() {
    const cart = getCart();
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
  }

  // Get cart item count (number of unique products)
  function getCartCount() {
    const cart = getCart();
    return cart.length; // Count unique products instead of total quantity
  }
  
  // Get total quantity of all items
  function getCartTotalQuantity() {
    const cart = getCart();
    return cart.reduce((count, item) => count + item.quantity, 0);
  }

  // Update cart badge
  function updateCartBadge() {
    const badge = document.getElementById('cartCount');
    if (badge) {
      const count = getCartCount();
      badge.textContent = count;
      badge.style.display = count > 0 ? 'flex' : 'none';
    }
  }

  // ==================== WISHLIST FUNCTIONS ====================

  // Get all wishlist items
  function getWishlist() {
    return getStorage(WISHLIST_KEY);
  }

  // Check if item is in wishlist
  function isInWishlist(productId) {
    const wishlist = getWishlist();
    return wishlist.some(item => item.id === productId);
  }

  // Add item to wishlist
  function addToWishlist(product) {
    const wishlist = getWishlist();
    
    if (isInWishlist(product.id)) {
      showToast('Already in wishlist', 'error');
      return false;
    }

    wishlist.push({
      id: product.id,
      name: product.name,
      price: product.price,
      image: product.image,
      slug: product.slug
    });

    setStorage(WISHLIST_KEY, wishlist);
    updateWishlistBadge();
    showToast(`${product.name} added to wishlist!`);
    return true;
  }

  // Remove item from wishlist
  function removeFromWishlist(productId) {
    let wishlist = getWishlist();
    wishlist = wishlist.filter(item => item.id !== productId);
    setStorage(WISHLIST_KEY, wishlist);
    updateWishlistBadge();
    showToast('Removed from wishlist', 'success');
    return true;
  }

  // Toggle wishlist (add if not present, remove if present)
  function toggleWishlist(product) {
    if (isInWishlist(product.id)) {
      removeFromWishlist(product.id);
      return false;
    } else {
      addToWishlist(product);
      return true;
    }
  }

  // Clear entire wishlist
  function clearWishlist() {
    setStorage(WISHLIST_KEY, []);
    updateWishlistBadge();
    showToast('Wishlist cleared', 'success');
  }

  // Get wishlist item count
  function getWishlistCount() {
    return getWishlist().length;
  }

  // Update wishlist badge
  function updateWishlistBadge() {
    const badge = document.getElementById('wishlistCount');
    if (badge) {
      const count = getWishlistCount();
      badge.textContent = count;
      badge.style.display = count > 0 ? 'flex' : 'none';
    }
  }

  // ==================== INITIALIZATION ====================

  // Initialize badges on page load
  function init() {
    updateCartBadge();
    updateWishlistBadge();
  }

  // Run init when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // ==================== EXPORT TO GLOBAL ====================

  window.DairyCart = {
    // Cart methods
    getCart,
    addToCart,
    removeFromCart,
    updateCartQuantity,
    clearCart,
    getCartTotal,
    getCartCount,
    getCartTotalQuantity,
    
    // Wishlist methods
    getWishlist,
    isInWishlist,
    addToWishlist,
    removeFromWishlist,
    toggleWishlist,
    clearWishlist,
    getWishlistCount,

    // Debug methods
    debugCart: function() {
      console.log('Cart:', getCart());
      console.log('Cart Count:', getCartCount());
      console.log('Cart Total Quantity:', getCartTotalQuantity());
      console.log('Cart Total:', getCartTotal());
    },
    debugWishlist: function() {
      console.log('Wishlist:', getWishlist());
      console.log('Wishlist Count:', getWishlistCount());
    },
    clearAll: function() {
      clearCart();
      clearWishlist();
      console.log('All data cleared');
    }
  };

  console.log('DairyCart module loaded successfully');

})();
