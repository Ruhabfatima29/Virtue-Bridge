<?php require_once __DIR__ . '/../../config/db.php'; ?>
<nav class="navbar">
    <div class="container">
        <div class="logo">
            <h2>Virtue Bridge</h2>
        </div>
        <!-- Hamburger Icon for Mobile View -->
        <div class="menu-toggle" onclick="toggleMenu()">☰</div>
        <ul class="nav-links">
            <li><a href="<?php echo BASE_URL; ?>src/views/home.php#hero-section">Home</a></li>
            <li><a href="<?php echo BASE_URL; ?>src/views/home.php#about">About Us</a></li>
            <li><a href="<?php echo BASE_URL; ?>src/views/home.php#projects">Projects</a></li>
            <li><a href="<?php echo BASE_URL; ?>src/views/loginView.php">Login</a></li>
            <li><a href="<?php echo BASE_URL; ?>src/views/loginView.php">Start a Project</a></li> 
        </ul>
    </div>
</nav>

<script>
function toggleMenu() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('show');
}

// Add event listeners to close the menu when an item is clicked
document.querySelectorAll('.nav-links a').forEach(item => {
    item.addEventListener('click', () => {
        const navLinks = document.querySelector('.nav-links');
        navLinks.classList.remove('show');
    });
});
</script>
