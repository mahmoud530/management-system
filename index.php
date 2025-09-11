<?php
include'connection.php';
$totalUsers = $connect->query("SELECT COUNT(*) FROM users")->fetchColumn();
// اجمالي الموظفين
$totalEmployees = $connect->query("SELECT COUNT(*) FROM employees")->fetchColumn();
// اجمالي الأقسام
$totalDepartments = $connect->query("SELECT COUNT(*) FROM departments")->fetchColumn();
$id = $_SESSION['id'] ?? '';
$name  = $_SESSION['name'] ?? '';
$role  = $_SESSION['role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharoas Tech - Software Solutions</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="icon" href="img/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Header Section -->
    <header class="header">
        <div class="logo">
            <img src="img/logo.svg" alt="Pharoas Tech Logo" class="hover-image" data-hover="img/logo.png">
        </div>
        <nav class="navigation">
            <ul>
                <li><a href="#" class="active">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#team">Our Team</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="auth-buttons">
            <a href="login.php" class="login-btn">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        </div>
        <?php else: ?>
        <div class="auth-buttons">
            <a href="profile.php" class="register-btn">Profile</a>
        </div>
        <?php endif; ?>
        <div class="menu-toggle">
            <i class="fas fa-bars"></i>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="part1">
        <div class="hero-content">
            <h1>Innovative Software Solutions</h1>
            <p>Transforming ideas into powerful digital experiences</p>
            <div class="cta-buttons">
                <a href="#services" class="primary-btn">Our Services</a>
                <a href="#contact" class="secondary-btn">Get in Touch</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="img/hero-image.svg" alt="Tech Illustration" class="hover-image"
                data-hover="img/hero-image.png">
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="part2">
        <div class="section-header">
            <h2>Our Services</h2>
            <p>Comprehensive software solutions tailored to your business needs</p>
        </div>
        <div class="services-container">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3>Custom Software Development</h3>
                <p>Tailored solutions designed to meet your specific business requirements and challenges.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Mobile App Development</h3>
                <p>Native and cross-platform mobile applications that deliver exceptional user experiences.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-cloud"></i>
                </div>
                <h3>Cloud Solutions</h3>
                <p>Scalable and secure cloud infrastructure to optimize your business operations.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Data Analytics</h3>
                <p>Transform your data into actionable insights to drive informed business decisions.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="part3">
        <div class="about-image">
            <img src="img/about-image.svg" alt="Our Office" class="hover-image" data-hover="img/about-image.jpg">
        </div>
        <div class="about-content">
            <h2>About Pharoas Tech</h2>
            <p>Founded in 2015, Pharoas Tech has been at the forefront of technological innovation, delivering
                cutting-edge software solutions to businesses worldwide.</p>
            <p>Our team of experienced developers, designers, and project managers work collaboratively to transform
                your ideas into reality, ensuring that every project exceeds expectations.</p>
            <div class="stats-container">
                <div class="stat-item">
                <h3><?php echo $totalUsers; ?>+</h3>
                    <p>Users</p>
                </div>
                <div class="stat-item">
                                    <h3><?php echo $totalEmployees; ?>+</h3>

                    <p>Employees</p>
                </div>
                <div class="stat-item">
                                   <h3><?php echo $totalDepartments; ?>+</h3>

                    <p>Departments</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="team" class="part4">
        <div class="section-header">
            <h2>Meet Our Team</h2>
            <p>The talented professionals behind our success</p>
        </div>
        <div class="team-container">
            <div class="team-member">
                <div class="member-image">
                    <img src="img/team-member1.svg" alt="Team Member" class="hover-image"
                        data-hover="img/team-member1.jpg">
                </div>
                <h3>Omar Nour</h3>
                <p>Electronics and Communication Engineer</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="team-member">
                <div class="member-image">
                    <img src="img/team-member2.svg" alt="Team Member" class="hover-image"
                        data-hover="img/team-member2.jpg">
                </div>
                <h3>Ahmed Dahawy</h3>
                <p>Software Engineer</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="team-member">
                <div class="member-image">
                    <img src="img/team-member3.svg" alt="Team Member" class="hover-image"
                        data-hover="img/team-member3.jpg">
                </div>
                <h3>Mahmoud Allam</h3>
                <p>Software Engineer</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="team-member">
                <div class="member-image">
                    <img src="img/team-member4.svg" alt="Team Member" class="hover-image"
                        data-hover="img/team-member4.jpg">
                </div>
                <h3>Nada</h3>
                <p>Software Engineer</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="part5">
        <div class="section-header">
            <h2>Client Testimonials</h2>
            <p>What our clients say about us</p>
        </div>
        <div class="testimonials-container">
            <div class="testimonial-card">
                <div class="quote-icon">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p class="testimonial-text">"Pharoas Tech delivered our project on time and exceeded our expectations.
                    Their team was professional, responsive, and truly understood our business needs."</p>
                <div class="client-info">
                    <img src="img/client1.svg" alt="Client" class="hover-image" data-hover="img/client1.jpg">
                    <div>
                        <h4>John Smith</h4>
                        <p>CEO, TechCorp</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="quote-icon">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p class="testimonial-text">"Working with Pharoas Tech was a game-changer for our business. Their
                    innovative solutions helped us streamline our operations and increase our revenue."</p>
                <div class="client-info">
                    <img src="img/client2.svg" alt="Client" class="hover-image" data-hover="img/client2.jpg">
                    <div>
                        <h4>Lisa Johnson</h4>
                        <p>COO, InnovateCo</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="part6">
        <div class="section-header">
            <h2>Get in Touch</h2>
            <p>Have a project in mind? Let's discuss how we can help.</p>
        </div>
        <div class="contact-container">
            <div class="contact-info">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>123 Tech Street, Silicon Valley, CA 94043</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <p>+1 (555) 123-4567</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <p>info@pharoastech.com</p>
                </div>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <form class="contact-form">
                <div class="form-group">
                    <input type="text" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Subject">
                </div>
                <div class="form-group">
                    <textarea placeholder="Your Message" rows="5" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="img/logo.svg" alt="Pharoas Tech Logo" class="hover-image" data-hover="img/logo.png">
                <p>Transforming ideas into powerful digital experiences</p>
            </div>
            <div class="footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#team">Our Team</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-services">
                <h3>Our Services</h3>
                <ul>
                    <li><a href="#">Custom Software Development</a></li>
                    <li><a href="#">Mobile App Development</a></li>
                    <li><a href="#">Cloud Solutions</a></li>
                    <li><a href="#">Data Analytics</a></li>
                </ul>
            </div>
            <div class="footer-newsletter">
                <h3>Subscribe to Our Newsletter</h3>
                <p>Stay updated with our latest news and offers</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Your Email" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Pharoas Tech. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="./js/script.js"></script>
</body>

</html>