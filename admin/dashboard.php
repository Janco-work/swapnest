<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard | SwapNest</title>
  <link rel="stylesheet" href="../css/styles.css" />
  <style>
    .dashboard-container { max-width: 1200px; margin: 2.3rem auto; background: #fff; padding: 2.3rem; border-radius: 14px; box-shadow: 0 2px 18px rgba(44,62,80,0.08);}
    .dashboard-container h1 { margin-bottom: 2.2rem;}
    .dashboard-section { margin-bottom: 2.2rem;}
    .dashboard-table { width: 100%; border-collapse: collapse; margin-bottom: 1rem;}
    .dashboard-table th, .dashboard-table td { padding: 0.77rem 1.2rem; border: 1px solid #eee;}
    .dashboard-table th { background: #f3f6fa; }
    .dashboard-table tr:nth-child(even) { background: #f9fbfc;}
    .logout-btn {
      margin-top: 1.7rem;
      background: #e53a3a;
      color: #fff;
      padding: 0.7rem 1.5rem;
      border: none;
      border-radius: 5px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.18s;
      float: right;
    }
    .logout-btn:hover {
      background: #b70909;
    }
    .action-btn, .add-btn { padding: 0.33rem 0.88rem; border-radius: 5px; font-size: 0.95rem; margin: 0 2px; border: none; cursor: pointer; }
    .edit-btn { background: #2d9cdb; color: #fff; }
    .delete-btn { background: #e54b4b; color: #fff; }
    .add-btn { background: #23324e; color: #fff; margin-bottom: 1.2rem; float: right;}
    .action-btn:hover, .add-btn:hover { opacity: 0.83; }
    .sold-btn { background: #10b981; color: #fff; }
    .unsold-btn { background: #b9b910; color: #fff; }
    /* Modal styles */
    .modal { display: none; position: fixed; z-index: 11; left: 0; top: 0; width: 100vw; height: 100vh; overflow: auto; background: rgba(30,30,30,0.25);}
    .modal-content { background: #fff; margin: 6% auto; padding: 2.5rem 2.1rem 2rem 2.1rem; border-radius: 13px; width: 100%; max-width: 380px; box-shadow: 0 8px 32px rgba(44,62,80,0.11); position: relative;}
    .close-btn { position: absolute; top: 13px; right: 17px; font-size: 1.45rem; color: #bbb; background: none; border: none; cursor: pointer; }
    .close-btn:hover { color: #2d9cdb;}
    .modal-content label { display:block; margin: 1.0rem 0 0.25rem 0; font-weight: 500;}
    .modal-content input, .modal-content textarea, .modal-content select {
      width: 100%; margin-bottom: 1rem; padding: 0.63rem 0.85rem; border: 1.3px solid #dde6ef; border-radius: 8px; background: #f6f7fa; font-size: 1.04rem;
    }
    .modal-content textarea { resize: vertical; min-height: 60px; max-height: 150px;}
    .modal-content button[type="submit"] {
      background: #23324e; color: #fff; border: none; padding: 0.67rem 2.1rem; border-radius: 8px; font-size: 1.06rem; font-weight: 700; cursor: pointer; transition: background 0.18s;
    }
    .modal-content button[type="submit"]:hover { background: #2d9cdb; }
    .modal-error { color: #e54b4b; margin-bottom: 1rem; text-align:center;}
    @media (max-width: 800px) {
      .dashboard-container { padding: 1rem; }
      .dashboard-table th, .dashboard-table td { padding: 0.49rem 0.5rem;}
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="brand">SwapNest <span style="color:#2d9cdb;font-weight:normal;font-size:1.1rem;">Admin</span></div>
    <ul class="nav-links">
      <li><a href="/index.php">Home</a></li>
      <li><a href="dashboard.php" class="active">Dashboard</a></li>
      <li><a href="logout.php" class="logout-btn">Logout</a></li>
    </ul>
  </nav>
  <div class="dashboard-container">
    <h1>Admin Dashboard</h1>
    
    <!-- USERS SECTION -->
    <div class="dashboard-section">
      <h2 style="display:inline;">All Users</h2>
      <table class="dashboard-table" id="usersTable">
        <thead>
          <tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th><th>Actions</th></tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    
    <!-- PRODUCTS SECTION -->
    <div class="dashboard-section">
      <h2 style="display:inline;">All Products</h2>
      <table class="dashboard-table" id="productsTable">
        <thead>
          <tr>
            <th>ID</th><th>Name</th><th>Description</th><th>Price</th>
            <th>Owner Email</th><th>Sold</th><th>Created</th><th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <!-- ORDERS SECTION -->
    <div class="dashboard-section">
      <h2>All Orders</h2>
      <table class="dashboard-table" id="ordersTable">
        <thead>
          <tr>
            <th>Order ID</th><th>Buyer</th><th>Seller</th><th>Product ID</th>
            <th>Status</th><th>Payment</th><th>Date</th><th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
  
  <!-- USER MODAL -->
  <div class="modal" id="userModal">
    <div class="modal-content">
      <button class="close-btn" onclick="closeUserModal()">&times;</button>
      <h3 id="userModalTitle">Add User</h3>
      <form id="userForm">
        <div class="modal-error" id="userModalError"></div>
        <input type="hidden" id="userId" />
        <label>Name:</label>
        <input type="text" id="userName" required />
        <label>Email:</label>
        <input type="email" id="userEmail" required />
        <label>Password:</label>
        <input type="password" id="userPassword" required />
        <button type="submit">Save User</button>
      </form>
    </div>
  </div>
  
  <!-- PRODUCT MODAL -->
  <div class="modal" id="productModal">
    <div class="modal-content">
      <button class="close-btn" onclick="closeProductModal()">&times;</button>
      <h3 id="productModalTitle">Add Product</h3>
      <form id="productForm">
        <div class="modal-error" id="productModalError"></div>
        <input type="hidden" id="productId" />
        <label>Name:</label>
        <input type="text" id="productName" required />
        <label>Description:</label>
        <textarea id="productDescription" required></textarea>
        <label>Price:</label>
        <input type="number" id="productPrice" required min="0" step="0.01" />
        <label>Owner Email:</label>
        <input type="email" id="productEmail" required />
        <label>Sold Status:</label>
        <select id="productSold">
          <option value="0">Not Sold</option>
          <option value="1">Sold</option>
        </select>
        <button type="submit">Save Product</button>
      </form>
    </div>
  </div>

  <!-- ORDER EDIT MODAL -->
  <div class="modal" id="orderModal">
    <div class="modal-content">
      <button class="close-btn" onclick="closeOrderModal()">&times;</button>
      <h3>Edit Order</h3>
      <form id="orderForm">
        <div class="modal-error" id="orderModalError"></div>
        <input type="hidden" id="orderId" />
        <label>Order Status:</label>
        <select id="orderStatus" required>
          <option value="pending">pending</option>
          <option value="shipped">shipped</option>
          <option value="delivered">delivered</option>
          <option value="cancelled">cancelled</option>
        </select>
        <label>Payment Status:</label>
        <select id="paymentStatus" required>
          <option value="paid">paid</option>
          <option value="unpaid">unpaid</option>
          <option value="refunded">refunded</option>
        </select>
        <button type="submit">Save Changes</button>
      </form>
    </div>
  </div>
  
  <script>
    // --- USERS CRUD ---
    function loadUsers() {
      fetch('get_users.php')
        .then(r => r.json())
        .then(users => {
          const tbody = document.querySelector('#usersTable tbody');
          if (!Array.isArray(users) || users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No users found.</td></tr>';
          } else {
            tbody.innerHTML = users.map(u => `
              <tr>
                <td>${u.id}</td>
                <td>${u.name}</td>
                <td>${u.email}</td>
                <td>${u.created_at || ''}</td>
                <td>
                  <!-- <button class="action-btn edit-btn" onclick="openUserModal('edit',${encodeURIComponent(JSON.stringify(u))})">Edit</button> -->
                  <button class="action-btn delete-btn" onclick="deleteUser(${u.id})">Delete</button>
                </td>
              </tr>
            `).join('');
          }
        });
    }

    function openUserModal(type) {
      document.getElementById('userModalTitle').textContent = 'Add User';
      document.getElementById('userId').value = '';
      document.getElementById('userName').value = '';
      document.getElementById('userEmail').value = '';
      document.getElementById('userPassword').value = '';
      document.getElementById('userModalError').textContent = '';
      document.getElementById('userModal').style.display = 'block';
    }

    function closeUserModal() {
      document.getElementById('userModal').style.display = 'none';
    }

    document.getElementById('userForm').onsubmit = function(e) {
      e.preventDefault();
      const name = document.getElementById('userName').value.trim();
      const email = document.getElementById('userEmail').value.trim();
      const password = document.getElementById('userPassword').value;
      if (!name || !email || !password) {
        document.getElementById('userModalError').textContent = 'All fields required.';
        return;
      }
      fetch('create_user.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ name, email, password })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          closeUserModal();
          loadUsers();
        } else {
          document.getElementById('userModalError').textContent = data.message || 'Error.';
        }
      });
    };

    function deleteUser(id) {
      if (!confirm('Delete this user?')) return;
      fetch('delete_user.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) loadUsers();
        else alert(data.message || 'Delete failed.');
      });
    }

    // --- PRODUCTS CRUD ---
    function loadProducts() {
      fetch('get_products.php')
        .then(r => r.json())
        .then(products => {
          const tbody = document.querySelector('#productsTable tbody');
          if (!Array.isArray(products) || products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">No products found.</td></tr>';
          } else {
            tbody.innerHTML = products.map(p => `
              <tr>
                <td>${p.id}</td>
                <td>${p.name}</td>
                <td>${p.description}</td>
                <td>R ${parseFloat(p.price).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                <td>${p.email}</td>
                <td>${p.sold == 1 ? '<span style="color:#e54b4b;">SOLD</span>' : '<span style="color:#1cb12b;">Available</span>'}
                    <button class="action-btn sold-btn" onclick="setSoldStatus(${p.id},${p.sold==1?0:1})">${p.sold==1?'Mark as Unsold':'Mark as Sold'}</button>
                </td>
                <td>${p.created_at || ''}</td>
                <td>
                  <!-- <button class="action-btn edit-btn" onclick="openProductModal('edit',${encodeURIComponent(JSON.stringify(p))})">Edit</button> -->
                  <button class="action-btn delete-btn" onclick="deleteProduct(${p.id})">Delete</button>
                </td>
              </tr>
            `).join('');
          }
        });
    }

    function openProductModal(type, productStr) {
      document.getElementById('productModalTitle').textContent = type === 'add' ? 'Add Product' : 'Edit Product';
      document.getElementById('productId').value = '';
      document.getElementById('productName').value = '';
      document.getElementById('productDescription').value = '';
      document.getElementById('productPrice').value = '';
      document.getElementById('productEmail').value = '';
      document.getElementById('productSold').value = '0';
      document.getElementById('productModalError').textContent = '';
      if (type === 'edit' && productStr) {
        const p = JSON.parse(decodeURIComponent(productStr));
        document.getElementById('productId').value = p.id;
        document.getElementById('productName').value = p.name;
        document.getElementById('productDescription').value = p.description;
        document.getElementById('productPrice').value = p.price;
        document.getElementById('productEmail').value = p.email;
        document.getElementById('productSold').value = p.sold;
      }
      document.getElementById('productModal').style.display = 'block';
    }

    function closeProductModal() {
      document.getElementById('productModal').style.display = 'none';
    }

    document.getElementById('productForm').onsubmit = function(e) {
      e.preventDefault();
      const id = document.getElementById('productId').value;
      const name = document.getElementById('productName').value.trim();
      const description = document.getElementById('productDescription').value.trim();
      const price = document.getElementById('productPrice').value;
      const email = document.getElementById('productEmail').value.trim();
      const sold = document.getElementById('productSold').value;
      if (!name || !description || !price || !email) {
        document.getElementById('productModalError').textContent = 'All fields required.';
        return;
      }
      let url = id ? 'update_product.php' : 'create_product.php';
      let body = id ? { id, name, description, price, email, sold } : { name, description, price, email };
      fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(body)
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          closeProductModal();
          loadProducts();
        } else {
          document.getElementById('productModalError').textContent = data.message || 'Error.';
        }
      });
    };

    function setSoldStatus(id, sold) {
      fetch('update_product.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id, sold })
      })
      .then(r => r.json())
      .then(data => { if (data.success) loadProducts(); });
    }

    function deleteProduct(id) {
      if (!confirm('Delete this product?')) return;
      fetch('delete_product.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) loadProducts();
        else alert(data.message || 'Delete failed.');
      });
    }

    // --- ORDERS CRUD ---
    function loadOrders() {
      fetch('get_orders.php')
        .then(r => r.json())
        .then(orders => {
          const tbody = document.querySelector('#ordersTable tbody');
          if (!Array.isArray(orders) || orders.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">No orders found.</td></tr>';
          } else {
            tbody.innerHTML = orders.map(o => `
              <tr>
                <td>${o.order_id}</td>
                <td>${o.buyer_email}</td>
                <td>${o.email}</td>
                <td>${o.product_id}</td>
                <td>${o.order_status}</td>
                <td>${o.payment_status}</td>
                <td>${o.order_date || ''}</td>
                <td>
                  <!-- <button class="action-btn edit-btn" onclick="openOrderModal(${encodeURIComponent(JSON.stringify(o))})">Edit</button> -->
                  <button class="action-btn delete-btn" onclick="deleteOrder(${o.order_id})">Delete</button>
                </td>
              </tr>
            `).join('');
          }
        });
    }

    /* function openOrderModal(orderStr) {
       const o = JSON.parse(decodeURIComponent(orderStr));
       document.getElementById('orderId').value = o.order_id;
       document.getElementById('orderStatus').value = o.order_status;
       document.getElementById('paymentStatus').value = o.payment_status;
       document.getElementById('orderModalError').textContent = '';
       document.getElementById('orderModal').style.display = 'block';
     } */

    function closeOrderModal() {
      document.getElementById('orderModal').style.display = 'none';
    }

     /* document.getElementById('orderForm').onsubmit = function(e) {
       e.preventDefault();
      const order_id = document.getElementById('orderId').value;
      const order_status = document.getElementById('orderStatus').value;
      const payment_status = document.getElementById('paymentStatus').value;
       fetch('update_order.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
         body: JSON.stringify({ order_id, order_status, payment_status })
       })
      .then(r => r.json())
       .then(data => {
        if (data.success) {
           closeOrderModal();
           loadOrders();
         } else {
           document.getElementById('orderModalError').textContent = data.message || 'Error.';
         }
       });
     }; */

    function deleteOrder(order_id) {
      if (!confirm('Delete this order?')) return;
      fetch('delete_order.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ order_id })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) loadOrders();
        else alert(data.message || 'Delete failed.');
      });
    }

    // --- Modal close on click outside
    window.onclick = function(event) {
      ['userModal','productModal','orderModal'].forEach(id=>{
        const m = document.getElementById(id);
        if (event.target === m) m.style.display = 'none';
      });
    }

    // --- INIT TABLES ---
    loadUsers();
    loadProducts();
    loadOrders();
  </script>
</body>
</html>
