function showToast(message, type = "info") {
  const container = document.getElementById('toast-container');
  if (!container) return;
  const toast = document.createElement('div');
  toast.className = 'toast-message ' + type;
  toast.textContent = message;
  // Custom close button (optional)
  const closeBtn = document.createElement('span');
  closeBtn.innerHTML = '&times;';
  closeBtn.style.marginLeft = '16px';
  closeBtn.style.cursor = 'pointer';
  closeBtn.style.fontWeight = 'bold';
  closeBtn.onclick = () => container.removeChild(toast);
  toast.appendChild(closeBtn);

  container.appendChild(toast);

  // Auto remove after 3 seconds
  setTimeout(() => {
    if (container.contains(toast)) container.removeChild(toast);
  }, 3200);
}

// Optional: fade-in animation
const style = document.createElement('style');
style.textContent = `
@keyframes fadeIn {from {opacity:0;} to {opacity:0.97;}}
.toast-message {transition:opacity 0.5s;}
`;
document.head.appendChild(style);

document.addEventListener("DOMContentLoaded", () => {

  // 1. DOM ELEMENTS

  const loginModal    = document.getElementById("loginModal");
  const registerModal = document.getElementById("registerModal");
  const cartModal     = document.getElementById("cartModal");
  const productModal  = document.getElementById("productModal");
  const adminModal    = document.getElementById("adminModal");
  const sellModal     = document.getElementById("sellModal");
  const checkoutModal = document.getElementById("checkoutModal");

  const loginBtn      = document.getElementById("loginBtn");
  const cartBtn       = document.getElementById("cartBtn");
  const sellBtn       = document.getElementById("sellBtn");
  const logoutBtn     = document.getElementById("logoutBtn");
  const adminLoginBtn = document.getElementById("adminLoginBtn");
  const closeCartModalBtn   = document.getElementById("closeCartModal");
  const closeAdminModalBtn  = document.getElementById("closeAdminModal");

  const adminLoginForm   = document.getElementById("adminLoginForm");
  const adminLoginError  = document.getElementById("adminLoginError");
  const checkoutBtn      = document.getElementById("checkoutBtn");
  const checkoutSummary  = document.getElementById("checkoutSummary");
  const checkoutForm     = document.getElementById("checkoutForm");
  const sellForm         = document.getElementById("sellForm");
  const cartCount        = document.getElementById("cartCount");
  const cartItemsDiv     = document.getElementById("cartItems");
  const cartEmptyMessage = document.getElementById("cartEmptyMessage");


  // 2. LOGIN STATE

  let userEmail    = localStorage.getItem('userEmail') || "";
  let isLoggedIn   = !!userEmail;
  let pendingCheckout = false;
  let cart         = [];

  function updateAuthUI() {
    if (isLoggedIn) {
      if (loginBtn) loginBtn.style.display = "none";
      if (logoutBtn) logoutBtn.style.display = "inline-block";
    } else {
      if (loginBtn) loginBtn.style.display = "inline-block";
      if (logoutBtn) logoutBtn.style.display = "none";
    }
  }
  updateAuthUI();

  function setUserLoggedIn(email) {
    isLoggedIn = true;
    userEmail = email;
    localStorage.setItem('userEmail', email);
    updateAuthUI();
  }

  function openLoginModal() { loginModal.style.display = 'block'; }


  // 3. PRODUCT GRID (Load Products)

  fetch('admin/get_products.php')
    .then(r => r.json())
    .then(products => {
      const grid = document.getElementById('product-grid');
      if (!Array.isArray(products) || products.length === 0) {
        grid.innerHTML = '<p style="text-align:center;">No products available.</p>';
      } else {
        grid.innerHTML = products.map(p => `
          <div class="product-card">
            <img src="uploads/${p.image}" alt="${p.name}">
            <h3>${p.name}</h3>
            <p>R ${p.price}</p>
            <button class="view-btn"
              data-name="${p.name}"
              data-price="R ${p.price}"
              data-description="${p.description}"
              data-id="${p.id}"
              data-image="uploads/${p.image}"
              data-email="${p.email}">View</button>
          </div>
        `).join('');
        setupViewButtons();
      }
    })
    .catch(() => {
      document.getElementById('product-grid').innerHTML =
        '<p style="text-align:center;color:red;">Error loading products.</p>';
    });

  function setupViewButtons() {
    document.querySelectorAll(".view-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        document.getElementById("modalName").textContent        = btn.getAttribute("data-name");
        document.getElementById("modalPrice").textContent       = btn.getAttribute("data-price");
        document.getElementById("modalDescription").textContent = btn.getAttribute("data-description");
        document.getElementById("modalImage").src               = btn.getAttribute("data-image");
        document.getElementById("modalProductId").value         = btn.getAttribute("data-id");
        document.getElementById("modalSellerEmail").value       = btn.getAttribute("data-email") || "";
        productModal.style.display = "block";
      });
    });
  }

  // 4. MODAL CLOSE LOGIC

  document.querySelectorAll(".close-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-close");
      document.getElementById(id).style.display = "none";
    });
  });
  if (closeCartModalBtn) closeCartModalBtn.onclick = () => cartModal.style.display = "none";
  if (closeAdminModalBtn) closeAdminModalBtn.onclick = () => adminModal.style.display = "none";

  window.onclick = function(event) {
    [loginModal, registerModal, cartModal, productModal, adminModal, sellModal, checkoutModal].forEach(modal => {
      if (event.target === modal) modal.style.display = "none";
    });
  };

  // 5. CART LOGIC

  function updateCartUI() {
    cartCount.textContent = cart.length > 0 ? `(${cart.length})` : "";
    if (cart.length === 0) {
      cartItemsDiv.innerHTML = "";
      if (cartEmptyMessage) cartEmptyMessage.style.display = "block";
      return;
    }
    if (cartEmptyMessage) cartEmptyMessage.style.display = "none";
    cartItemsDiv.innerHTML = "";
    cart.forEach((item, idx) => {
      cartItemsDiv.innerHTML += `
        <div class="cart-item">
          <div class="cart-item-info">
            <span class="cart-item-title">${item.name}</span>
            <span class="cart-item-price">${item.price}</span>
          </div>
          <button class="removeBtn" data-index="${idx}" title="Remove">
            <span class="remove-x">&times;</span>
          </button>
        </div>
      `;
    });
    cartItemsDiv.querySelectorAll(".removeBtn").forEach(btn => {
      btn.onclick = () => {
        cart.splice(Number(btn.dataset.index), 1);
        updateCartUI();
      };
    });
  }

  cartBtn.onclick = (e) => {
    e.preventDefault();
    if (cart.length === 0) return;
    updateCartUI();
    cartModal.style.display = "block";
  };

  document.getElementById("addToCartBtn").onclick = () => {
    if (!isLoggedIn) {
      productModal.style.display = "none";
      openLoginModal();
      return;
    }
    cart.push({
      name:  document.getElementById("modalName").textContent,
      price: document.getElementById("modalPrice").textContent,
      id:    document.getElementById("modalProductId").value,
      email: document.getElementById("modalSellerEmail").value
    });
    updateCartUI();
    showToast("Added to cart!", "info");
    productModal.style.display = "none";
  };


  // 6. LOGIN & REGISTRATION

  loginBtn.onclick = (e) => {
    e.preventDefault();
    openLoginModal();
  };

  document.getElementById("showRegister").onclick = e => {
    e.preventDefault();
    loginModal.style.display = "none";
    registerModal.style.display = "block";
  };

  document.getElementById("showLogin").onclick = e => {
    e.preventDefault();
    registerModal.style.display = "none";
    openLoginModal();
  };

  document.getElementById("loginForm").onsubmit = async function(e) {
    e.preventDefault();
    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("loginPassword").value;
    if (!email || !password) return showToast('Please enter both email and password.', "error");
    try {
      const res = await fetch("php/login.php", {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ email, password })
      });
      const data = await res.json();
      if (data.success) {
        showToast(`Welcome back, ${data.name}!`, "info");
        setUserLoggedIn(email);
        loginModal.style.display = "none";
        if (pendingCheckout) { showCheckoutModal(); pendingCheckout = false; }
      } else {
        showToast("Login failed: " + data.message, "error");
      }
    } catch {
      showToast("Something went wrong. Please try again.", "error");
    }
  };

  document.getElementById("registerForm").onsubmit = async function(e) {
    e.preventDefault();
    const name = document.getElementById("registerName").value.trim();
    const email = document.getElementById("registerEmail").value.trim();
    const password = document.getElementById("registerPassword").value;
    if (!name || !email || !password) return showToast('Please fill in all fields.', "info");
    try {
      const res = await fetch("php/register.php", {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ name, email, password })
      });
      const data = await res.json();
      if (data.success) {
        showToast("Registration successful! You can now login.", "success");
        this.reset();
        registerModal.style.display = "none";
        openLoginModal();
      } else {
        showToast("Registration failed: " + data.message, "error");
      }
    } catch {
      showToast("Error communicating with server.", "error");
    }
  };

  // 7. SELL PRODUCT

  sellBtn.addEventListener('click', function(e) {
    e.preventDefault();
    if (!isLoggedIn) {
      openLoginModal();
      return;
    }
    sellModal.style.display = "block";
  });

  if (sellForm) {
    sellForm.onsubmit = async function(e) {
      e.preventDefault();
      if (!isLoggedIn) {
        openLoginModal();
        return;
      }
      const formData = new FormData(sellForm);
      formData.append("user_email", userEmail);
      try {
        const res = await fetch("php/sell_product.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        if (data.success) {
          showToast("Product listed successfully!");
          sellForm.reset();
          sellModal.style.display = "none";
        } else {
          showToast("Failed to list product: " + data.message);
        }
      } catch {
        showToast("Server error.", "error");
      }
    };
  }

  // 8. ADMIN LOGIN

  if (adminLoginBtn) {
    adminLoginBtn.onclick = function() {
      adminModal.style.display = 'block';
      adminLoginError.style.display = 'none';
      adminLoginError.textContent = '';
    };
  }
  if (closeAdminModalBtn) {
    closeAdminModalBtn.onclick = function() {
      adminModal.style.display = 'none';
    };
  }
  if (adminLoginForm) {
    adminLoginForm.onsubmit = async function(e) {
      e.preventDefault();
      const email = document.getElementById("admin-email").value.trim();
      const password = document.getElementById("admin-password").value;
      try {
        const res = await fetch("admin/admin_login.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email, password })
        });
        const data = await res.json();
        if (data.success) {
          adminModal.style.display = 'none';
          showToast("Admin login successful!", "success");
          document.getElementById("adminDashboardLink").style.display = "inline-block";
          window.location.href = "admin/dashboard.php";
        } else {
          adminLoginError.textContent = data.message || 'Login failed';
          adminLoginError.style.display = 'block';
        }
      } catch {
        adminLoginError.textContent = 'Server error';
        adminLoginError.style.display = 'block';
      }
    };
  }

  // 9. CHECKOUT

  checkoutBtn.onclick = function() {
    if (!isLoggedIn) {
      cartModal.style.display = "none";
      openLoginModal();
      pendingCheckout = true;
      return;
    }
    showCheckoutModal();
  };

  function showCheckoutModal() {
    let summary = "<ul style='text-align:left;'>";
    let total = 0;
    cart.forEach(item => {
      summary += `<li>${item.name} — ${item.price}</li>`;
      total += Number(item.price.replace(/[^\d.]/g, '')) || 0;
    });
    summary += `</ul><p><strong>Total:</strong> <span style="color:#2d9cdb;font-size:1.21rem;">R ${total.toLocaleString()}</span></p>`;
    checkoutSummary.innerHTML = summary;
    checkoutModal.style.display = "block";
  }

 if (checkoutForm) {
  checkoutForm.onsubmit = async function(e) {
    e.preventDefault();
    if (!userEmail) {
      openLoginModal();
      return;
    }

    // ---- CUSTOM VALIDATION ----
    const cardName = document.getElementById("paymentName").value.trim();
    const cardNumber = document.getElementById("paymentCard").value.trim();
    const cardExp = document.getElementById("paymentExp").value.trim();
    const cardCvv = document.getElementById("paymentCvv").value.trim();
    const address = document.getElementById("paymentAddress").value.trim();

    if (!/^MR\. |^MRS\. /i.test(cardName)) {
      showToast('Card name must start with "MR. " or "MRS. "', "info");
      return false;
    }
    if (!/^\d{16}$/.test(cardNumber)) {
      showToast('Card number must be exactly 16 digits.', "info");
      return false;
    }
    if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(cardExp)) {
      showToast('Expiry date must be in format MM/YY with a valid month.', "info");
      return false;
    }
    if (!/^\d{3}$/.test(cardCvv)) {
      showToast('CVV must be exactly 3 numbers.', "info");
      return false;
    }
    if (!address) {
      showToast('Please enter a shipping address.', "info");
      return false;
    }
    // ---- END VALIDATION ----

    const formData = {
      buyer_email: userEmail,
      items: cart,
      payment: {
        name: cardName,
        card: cardNumber,
        exp: cardExp,
        cvv: cardCvv,
        address: address
      }
    };
    try {
      const res = await fetch("php/place_order.php", {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(formData)
      });
      const data = await res.json();
      if (data.success) {
        showToast("Payment successful! Your order has been placed.", "isuccess");
        cart = [];
        updateCartUI();
        checkoutModal.style.display = "none";
        cartModal.style.display = "none";
      } else {
        showToast("Order failed: " + data.message);
      }
    } catch (err) {
      showToast("Server error processing order.", "error");
    }
  };
}



  // 10. LOGOUT

  if (logoutBtn) {
    logoutBtn.onclick = function(e) {
      e.preventDefault();
      localStorage.removeItem('userEmail');
      isLoggedIn = false;
      userEmail = "";
      updateAuthUI();
      showToast("You have been logged out.", "info");
    }
  };

  // Initialize Cart UI on load
  updateCartUI();
});
