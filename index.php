<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SwapNest - Home</title>
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <div class="page-wrapper">
    <!-- NAVBAR -->
    <nav class="navbar">
      <div class="brand">SwapNest</div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="#" id="sellBtn">Sell</a></li>
        <li><a href="#" id="cartBtn">Cart <span id="cartCount"></span></a></li>
        <li><a href="#" id="loginBtn">Login</a></li>
        <li><a href="#" id="adminLoginBtn">Admin Login</a></li>
        <li><a href="#" id="logoutBtn">Logout</a></li>
        <li><a href="admin/dashboard.php" id="adminDashboardLink" style="display:none;">Dashboard</a></li>
      </ul>
    </nav>

    <!-- HERO SECTION -->
    <header class="hero">
      <h1>Welcome to SwapNest</h1>
      <p>Buy and sell gadgets easily with other users in your community</p>
    </header>

    <!-- PRODUCT GRID -->
    <main id="product-grid" class="product-grid">
      <!-- Product cards are dynamically loaded here -->
    </main>

    <!-- PRODUCT MODAL -->
    <div id="productModal" class="modal">
      <div class="modal-content">
        <span class="close-btn" data-close="productModal">&times;</span>
        <img id="modalImage" src="" alt="Product Image" />
        <h2 id="modalName">Product Name</h2>
        <p id="modalPrice">R 0.00</p>
        <p id="modalDescription">Product description goes here.</p>
        <input type="hidden" id="modalProductId">
        <input type="hidden" id="modalSellerEmail">
        <button id="addToCartBtn">Add to Cart</button>
      </div>
    </div>

    <!-- LOGIN MODAL -->
    <div id="loginModal" class="modal">
      <div class="modal-content">
        <span class="close-btn" data-close="loginModal">&times;</span>
        <h2>Login</h2>
        <form id="loginForm">
          <label for="loginEmail">Email:</label>
          <input type="email" id="loginEmail" required />
          <label for="loginPassword">Password:</label>
          <input type="password" id="loginPassword" required />
          <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="#" id="showRegister">Register here</a></p>
      </div>
    </div>

    <!-- ADMIN LOGIN MODAL -->
    <div id="adminModal" class="modal">
      <div class="modal-content">
        <span class="close" id="closeAdminModal">&times;</span>
        <h2>Admin Login</h2>
        <form id="adminLoginForm">
          <input type="email" id="admin-email" placeholder="Email" required>
          <input type="password" id="admin-password" placeholder="Password" required>
          <button type="submit">Login</button>
          <div id="adminLoginError" style="color:red;margin-top:8px;display:none;"></div>
        </form>
      </div>
    </div>

    <!-- REGISTER MODAL -->
    <div id="registerModal" class="modal">
      <div class="modal-content">
        <span class="close-btn" data-close="registerModal">&times;</span>
        <h2>Register</h2>
        <form id="registerForm">
          <label for="registerName">Name:</label>
          <input type="text" id="registerName" required />
          <label for="registerEmail">Email:</label>
          <input type="email" id="registerEmail" required />
          <label for="registerPassword">Password:</label>
          <input type="password" id="registerPassword" required />
          <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="#" id="showLogin">Login here</a></p>
      </div>
    </div>

    <!-- CART MODAL -->
    <div id="cartModal" class="modal">
      <div class="modal-content">
        <span class="close" id="closeCartModal">&times;</span>
        <h2>Your Cart</h2>
        <div id="cartItems"></div>
        <div id="cartEmptyMessage" style="display:none;text-align:center;margin-top:1rem;">Your cart is empty!</div>
        <button id="checkoutBtn" type="button" style="width:100%;margin-top:1rem;">Checkout</button>
      </div>
    </div>

    <!-- SELL MODAL -->
    <div id="sellModal" class="modal">
      <div class="modal-content">
        <span class="close-btn" data-close="sellModal">&times;</span>
        <h2>Sell an Item</h2>
        <form id="sellForm" enctype="multipart/form-data">
          <label for="sellName">Product Name:</label>
          <input type="text" id="sellName" name="name" required />

          <label for="sellDescription">Description:</label>
          <textarea id="sellDescription" name="description" rows="3" required></textarea>

          <label for="sellPrice">Price (ZAR):</label>
          <input type="number" id="sellPrice" name="price" required min="0" />

          <label for="sellImage">Product Image:</label>
          <input type="file" id="sellImage" name="image" accept="image/*" required />

          <button type="submit">List Product</button>
        </form>
      </div>
    </div>

    <!-- CHECKOUT MODAL -->
    <div id="checkoutModal" class="modal">
      <div class="modal-content">
        <span class="close-btn" data-close="checkoutModal">&times;</span>
        <h2>Checkout</h2>
        <div id="checkoutSummary"></div>
        <form id="checkoutForm">
          <label for="paymentName">Name on Card:</label>
          <input type="text" id="paymentName" name="paymentName" required />
          <label for="paymentCard">Card Number:</label>
          <input type="text" id="paymentCard" name="paymentCard" required maxlength="19" />
          <label for="paymentExp">Expiration Date:</label>
          <input type="text" id="paymentExp" name="paymentExp" placeholder="MM/YY" required maxlength="5" />
          <label for="paymentCvv">CVV:</label>
          <input type="text" id="paymentCvv" name="paymentCvv" required maxlength="4" />
          <button type="submit">Confirm Purchase</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="footer-container">
      <div class="footer-brand">
        <h3>SwapNest</h3>
        <p>&copy; 2025 SwapNest. All rights reserved.</p>
      </div>
      <div class="footer-links">
        <a href="#">Terms &amp; Conditions</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Cookies</a>
        <a href="#">Help</a>
        <a href="mailto:support@swapnest.com">Contact Us</a>
      </div>
    </div>
  </footer>

  <div id="toast-container" style="position:fixed;bottom:28px;right:28px;z-index:9999;display:flex;flex-direction:column;gap:12px;"></div>

  <!-- SCRIPTS -->
  <script src="js/scripts.js"></script>
</body>
</html>
