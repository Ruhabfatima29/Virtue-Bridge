<?php require_once __DIR__ . '/../../config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtue Bridge - Home</title>
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/home.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/navbar.css"> <!-- Added navbar CSS file -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Include Navbar -->
    <?php include(ROOT_PATH . 'src/includes/navbar.php'); ?>

    <!-- Hero Section -->
    <section class="hero" id="hero-section">
        <div class="hero-content">
            <h1>Welcome to Virtue Bridge</h1>
            <p>Empowering communities through education and opportunities.</p>
            <a href="#about" class="btn-primary">Discover More</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section about">
        <div class="about-container">
            <h1>About Us</h1>
            <p>Virtue Bridge connects communities with impactful initiatives.</p>
        </div>

        <!-- Carousel Section -->
        <div class="carousel-container">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <div class="slide-content">
                            <img src="<?php echo BASE_URL; ?>assets/images/home.jpg" alt="Empowering Education">
                            <h3>Empowering Education</h3>
                            <p>We strive to make education accessible and impactful for all.</p>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div class="slide-content">
                            <img src="<?php echo BASE_URL; ?>assets/images/leader.jpg" alt="Building Communities">
                            <h3>Building Communities</h3>
                            <p>Collaborating with local leaders to create lasting change.</p>
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <div class="slide-content">
                            <img src="<?php echo BASE_URL; ?>assets/images/sustainbility.jpg" alt="Sustainability">
                            <h3>Sustainability</h3>
                            <p>Our projects aim for sustainable impact that lasts generations.</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

<!-- Top Donors Section -->


<section id="top-donors" class="section">
    <div class="section-header">
        <h2>Our Generous Supporters</h2>
        <p class="section-subtitle">Making a difference in our community</p>
    </div>

    <div class="donors-grid">
        <?php
        include_once(ROOT_PATH . 'src/controllers/campaignController.php');
        
        try {
            $projects = fetchRecentProjects(2);
            $topDonors = fetchTopDonors(5);

            if (empty($topDonors)) {
                echo '<div class="no-donors">Be the first to support our cause!</div>';
                return;
            }

            foreach ($topDonors as $donor):
                // Secure data masking
                $maskedEmail = preg_replace('/(?<=.{3}).(?=.*@)/u', '*', htmlspecialchars($donor['email']));
                
                // Donor level determination
                $donorLevel = match(true) {
                    $donor['total_donated'] >= 1000 => [
                        'level' => 'Platinum Supporter',
                        'icon' => 'fa-gem',
                        'class' => 'platinum'
                    ],
                    $donor['total_donated'] >= 100 => [
                        'level' => 'Gold Supporter',
                        'icon' => 'fa-award',
                        'class' => 'gold'
                    ],
                    default => [
                        'level' => 'Silver Supporter',
                        'icon' => 'fa-medal',
                        'class' => 'silver'
                    ]
                };
        ?>
                <div class="donor-card <?php echo $donorLevel['class']; ?>">
                    <div class="donor-header">
                        <div class="donor-level">
                            <i class="fas <?php echo $donorLevel['icon']; ?>" 
                               aria-hidden="true"></i>
                            <span><?php echo $donorLevel['level']; ?></span>
                        </div>
                        <div class="donation-count">
                            <strong><?php echo number_format((int)$donor['donation_count']); ?></strong>
                            <?php echo $donor['donation_count'] === 1 ? 'Donation' : 'Donations'; ?>
                        </div>
                    </div>

                    <div class="donor-info">
                        <div class="donor-stats">
                            <div class="stat">
                                <h3>$<?php echo number_format((float)$donor['total_donated'], 2); ?></h3>
                                <p>Total Impact</p>
                            </div>
                            <div class="stat">
                                <h3><?php echo $maskedEmail; ?></h3>
                                <p>Email</p>
                            </div>
                        </div>
                    </div>
                </div>
        <?php 
            endforeach;
        } catch (Exception $e) {
            error_log("Error in donor section: " . $e->getMessage());
            echo '<div class="error-message">Unable to load donor information. Please try again later.</div>';
        }
        ?>
    </div>
</section>
<!-- Projects Section -->


<div id="projects" class="homepage-projects">
        <h2>Featured Campaigns</h2>
        <div class="project-cards">
            <?php foreach ($projects as $project):
                // Dynamically determine the image path based on the campaign type or ID
                $imagePath = BASE_URL . "assets/images/campaigns/" . htmlspecialchars($project['category_type']) . ".jpg";
            ?>
                <div class="project-card">
                    <!-- Campaign Image -->
                    <div class="project-image">
                        <img src="<?php echo $imagePath; ?>" alt="Campaign Image" class="campaign-img">
                    </div>
                    <!-- Campaign Details -->
                    <div class="project-details">
                        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p><?php echo htmlspecialchars($project['description']); ?></p>
                        <p><strong>Goal:</strong> $<?php echo htmlspecialchars($project['goal_amount']); ?></p>
                        <p><strong>Raised:</strong> $<?php echo htmlspecialchars($project['current_amount']); ?></p>
                        <!-- Donate Button -->
                        <form action="<?php echo BASE_URL; ?>stripe/donate.php" method="GET">
                            <input type="hidden" name="campaign_id" value="<?php echo htmlspecialchars($project['campaign_id']); ?>">
                            <button type="submit" class="donate-btn">Donate Now</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="<?php echo BASE_URL; ?>src/views/allCampaigns.php" class="view-more-btn">View More Campaigns</a>
    </div>


    <!-- Include Contact -->
    <?php include(ROOT_PATH . 'src/views/contactForm.php'); ?>

    <!-- Footer -->
    <footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#about">About Us</a></li>
                <li><a href="#projects">Our Projects</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="/privacy">Privacy Policy</a></li>
            </ul>
        </div>
        
        <div class="footer-section contact-info">
            <h3>Contact Us</h3>
            <ul>
                <li><i class="fas fa-map-marker-alt"></i> 123 Bridge Street, City, Country</li>
                <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                <li><i class="fas fa-envelope"></i> info@virtuebridge.org</li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h3>Newsletter</h3>
            <p>Stay updated with our latest projects and initiatives.</p>
            
        </div>
    </div>
    
    <div class="footer-bottom">
        <ul class="social-links">
            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
        </ul>
        <p>&copy; 2024 Virtue Bridge. All rights reserved.</p>
    </div>
</footer>

    <!-- Include Swiper.js -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 5000,
            },
        });
        // Add this to your existing JavaScript
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <style>
        .swiper-container {
            margin: 20px auto;
            /* Prevent it from affecting the navbar */
            max-width: 90%;
            /* Keep it responsive */
            overflow: hidden;
        }
    </style>
</body>

</html>