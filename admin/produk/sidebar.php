<aside class="sidebar-nav-wrapper">
  <div class="navbar-logo">
    <a href="#">
      Toko Motor Backend System
    </a>
  </div>
  <nav class="sidebar-nav">
    <ul>
      <li class="nav-item">
        <a href="../">
          <span class="icon">
            <i class="fas fa-home"></i>
          </span>
          <span class="text">Dashboard</span>
        </a>
      </li>
      <li class="nav-item active">
        <a href="#">
          <span class="icon">
            <i class="fas fa-folder"></i>
          </span>
          <span class="text">Kelola Produk</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="../pesanan.php">
          <span class="icon">
            <i class="fas fa-file-invoice-dollar"></i>
          </span>
          <span class="text">Data Pesanan</span>
        </a>
      </li>
      <?php if (!isset($_SESSION['super_userxxx'])) : ?>
        <li class="nav-item">
          <a href="../login.php">
            <span class="icon">
              <i class="fas fa-sign-in-alt"></i>
            </span>
            <span class="text">Login</span>
          </a>
        </li>
      <?php else : ?>
        <li class="nav-item">
          <a href="../logout.php">
            <span class="icon">
              <i class="fas fa-sign-out-alt"></i>
            </span>
            <span class="text">Logout</span>
          </a>
        </li>
      <?php endif; ?>
      <span class="divider">
        <hr />
      </span>
</aside>
<div class="overlay"></div>