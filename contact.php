<?php
$pageTitle = "Contact Us - industry.co.zw";
$pageDescription = "Get in touch with industry.co.zw - Zimbabwe's leading industrial portal. List your business, inquire about advertising, or ask any questions.";
require_once __DIR__ . '/includes/head.php';
?>
<style>
    body.index-page { padding-top: 85px; }
    
    /* BREADCRUMB STYLES */
    .breadcrumb-wrapper {
        background: #f8f9fa;
        padding: 12px 20px;
        border-bottom: 1px solid #e0e0e0;
    }
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 13px;
    }
    .breadcrumb li {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .breadcrumb li:not(:last-child):after {
        content: "›";
        color: #999;
        font-size: 16px;
    }
    .breadcrumb a {
        color: #2e7d32;
        text-decoration: none;
        transition: color 0.2s;
    }
    .breadcrumb a:hover {
        color: #1b5e20;
        text-decoration: underline;
    }
    .breadcrumb .current {
        color: #666;
        font-weight: 500;
    }
    .breadcrumb i {
        font-size: 14px;
        color: #2e7d32;
    }

    /* Page Title */
    .page-title {
        background: linear-gradient(135deg, #1a5e2a, #0d3b1a);
        padding: 50px 0 40px;
        text-align: center;
        color: #fff;
    }
    .page-title h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #fff;
    }
    .page-title p {
        color: rgba(255,255,255,0.85);
        font-size: 16px;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Section Headers */
    .section-header {
        margin-bottom: 30px;
        text-align: center;
    }
    .section-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a2c3e;
        margin-bottom: 10px;
    }
    .section-header p {
        color: #666;
        font-size: 14px;
    }

    /* Contact Info Cards */
    .info-card {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        height: 100%;
        text-align: center;
        transition: all 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .info-card .icon {
        font-size: 40px;
        color: #2e7d32;
        margin-bottom: 15px;
    }
    .info-card h3 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #1a2c3e;
    }
    .info-card p {
        font-size: 14px;
        color: #666;
        margin: 0;
    }

    /* Contact Form */
    .form-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .form-card h3 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2c3e;
        margin-bottom: 8px;
    }
    .form-card p {
        color: #666;
        font-size: 14px;
        margin-bottom: 25px;
    }
    .form-label {
        font-weight: 600;
        font-size: 13px;
        color: #333;
        margin-bottom: 5px;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2e7d32;
        box-shadow: 0 0 0 3px rgba(46,125,50,0.1);
    }
    textarea.form-control {
        resize: vertical;
    }
    .submit-btn {
        background: #2e7d32;
        color: #fff;
        border: none;
        padding: 12px 30px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
    }
    .submit-btn:hover {
        background: #1b5e20;
        transform: translateY(-2px);
    }
    .submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Map Container */
    .map-container {
        border-radius: 12px;
        overflow: hidden;
        margin-top: 15px;
        height: 250px;
    }
    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* Service Items */
    .service-item {
        background: #fff;
        border-radius: 12px;
        padding: 25px 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    .service-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .service-item .icon {
        font-size: 40px;
        color: #2e7d32;
        margin-bottom: 15px;
    }
    .service-item h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #1a2c3e;
    }
    .service-item p {
        font-size: 13px;
        color: #666;
        line-height: 1.5;
    }

    /* FAQ Styles - FIXED CLICKABLE */
    .faq-container {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .faq-item {
        border-bottom: 1px solid #f0f0f0;
        padding: 0;
        cursor: pointer;
    }
    .faq-item:last-child {
        border-bottom: none;
    }
    .faq-question {
        padding: 18px 40px 18px 0;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        cursor: pointer;
    }
    .faq-question .faq-icon {
        font-size: 20px;
        color: #2e7d32;
        flex-shrink: 0;
    }
    .faq-question h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1a2c3e;
        margin: 0;
        flex: 1;
    }
    .faq-question .faq-toggle {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #999;
        transition: transform 0.2s;
    }
    .faq-item.active .faq-question .faq-toggle {
        transform: translateY(-50%) rotate(90deg);
        color: #2e7d32;
    }
    .faq-content {
        display: none;
        padding: 0 0 18px 32px;
        font-size: 13px;
        color: #666;
        line-height: 1.5;
    }
    .faq-item.active .faq-content {
        display: block;
    }
    .faq-question:hover {
        background: #f8f9fa;
    }

    /* Alert Messages */
    .alert-message {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 13px;
        display: none;
    }
    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }
    .alert-error {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
    }
    .alert-loading {
        background: #e3f2fd;
        color: #1565c0;
        border: 1px solid #bbdefb;
    }

    @media (max-width: 768px) {
        body.index-page { padding-top: 70px; }
        .page-title { padding: 30px 0 20px; }
        .page-title h1 { font-size: 24px; }
        .section-header h2 { font-size: 22px; }
        .form-card { padding: 20px; }
        .info-card { padding: 20px; }
        .faq-question h3 { font-size: 14px; }
        .faq-content { padding-left: 20px; }
    }
</style>
</head>

<body class="index-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main class="main">

    <!-- BREADCRUMB NAVIGATION -->
    <div class="breadcrumb-wrapper">
        <ul class="breadcrumb">
            <li><a href="<?= SITE_ROOT ?>/index.php"><i class="bi bi-house-door"></i> Home</a></li>
            <li><span class="current">Contact</span></li>
        </ul>
    </div>

    <!-- PAGE TITLE -->
    <section class="page-title">
        <div class="container">
            <h1>Contact Us</h1>
            <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>
    </section>

    <section style="padding: 40px 0 60px; background: #f5f5f5;">
        <div class="container">

            <!-- Contact Info Row -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="icon"><i class="bi bi-geo-alt"></i></div>
                        <h3>Visit Us</h3>
                        <p>69 Samora Machel Avenue, Bard House<br>Harare, Zimbabwe</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="icon"><i class="bi bi-telephone"></i></div>
                        <h3>Call Us</h3>
                        <p>+263 242 123456</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="icon"><i class="bi bi-envelope"></i></div>
                        <h3>Email Us</h3>
                        <p>info@industry.co.zw</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form and Map Row -->
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="form-card" style="height: 100%;">
                        <h3>Our Location</h3>
                        <p>Find us in the heart of Harare's business district</p>
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d121797.794414345!2d30.96413195!3d-17.82516625!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1931a4a0b7ffdb8d%3A0xca02492e2f2b1c1b!2sHarare%2C%20Zimbabwe!5e0!3m2!1sen!2sus!4v1697000000000!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-card">
                        <h3>Send us a Message</h3>
                        <p>Fill out the form below and we'll get back to you within 24 hours.</p>
                        
                        <div id="alertMessage"></div>
                        
                        <form id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Your Name *</label>
                                    <input type="text" class="form-control" id="nameField" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Your Email *</label>
                                    <input type="email" class="form-control" id="emailField" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phoneField" placeholder="+263...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Subject *</label>
                                    <select class="form-select" id="subjectField" required>
                                        <option value="">Select a subject...</option>
                                        <option value="List My Business">List My Business</option>
                                        <option value="Advertising Inquiry">Advertising Inquiry</option>
                                        <option value="CZI Membership">CZI Membership</option>
                                        <option value="CIFOZ Membership">CIFOZ Membership</option>
                                        <option value="Tender Information">Tender Information</option>
                                        <option value="General Inquiry">General Inquiry</option>
                                        <option value="Technical Support">Technical Support</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message *</label>
                                    <textarea class="form-control" id="messageField" rows="6" placeholder="Tell us how we can help you..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="submit-btn" id="submitBtn">
                                        <i class="bi bi-send"></i> Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Contact Us Section -->
    <section style="padding: 40px 0; background: #fff;">
        <div class="container">
            <div class="section-header">
                <h2>Why Get Listed on industry.co.zw?</h2>
                <p>Benefits of joining Zimbabwe's leading industrial portal</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="service-item">
                        <div class="icon"><i class="bi bi-eye"></i></div>
                        <h4>Visibility</h4>
                        <p>Get your business seen by thousands of potential customers, partners, and stakeholders across Zimbabwe and beyond.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-item">
                        <div class="icon"><i class="bi bi-people"></i></div>
                        <h4>Networking</h4>
                        <p>Connect with CZI and CIFOZ members, attend industry events, and build valuable business relationships.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-item">
                        <div class="icon"><i class="bi bi-file-text"></i></div>
                        <h4>Tender Access</h4>
                        <p>Stay informed about active tenders and business opportunities across all industries in Zimbabwe.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section style="padding: 40px 0 60px; background: #f5f5f5;">
        <div class="container">
            <div class="section-header">
                <h2>Frequently Asked Questions</h2>
                <p>Common questions about listing your business</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="faq-container" id="faqContainer">
                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="faq-icon bi bi-question-circle"></i>
                                <h3>How do I list my company on Industry?</h3>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <div class="faq-content">
                                <p>Simply fill out the contact form above with your company details, or email us directly at info@industry.co.zw. Our team will add your company to the appropriate industry and province categories.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="faq-icon bi bi-question-circle"></i>
                                <h3>Is there a cost to list my business?</h3>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <div class="faq-content">
                                <p>Basic listings are free. For enhanced listings with advertising options, logos, banners, and priority placement, please contact us for our advertising packages tailored for CZI and CIFOZ members.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="faq-icon bi bi-question-circle"></i>
                                <h3>How long does it take to get listed?</h3>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <div class="faq-content">
                                <p>Most listings are processed within 24-48 hours. Once your company is added, it will appear in the relevant industry and province pages, searchable by all visitors to the portal.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="faq-icon bi bi-question-circle"></i>
                                <h3>Can I update my company information later?</h3>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <div class="faq-content">
                                <p>Yes! Just contact us with your updated information and we'll make the changes. We recommend keeping your listing up to date with current contact details, logos, and descriptions.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="faq-icon bi bi-question-circle"></i>
                                <h3>How do I advertise on industry.co.zw?</h3>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <div class="faq-content">
                                <p>We offer various advertising options including banner ads, company logos, downloadable flyers, and event posters. Contact us through the form above or email info@industry.co.zw for our advertising rate card.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    const API_BASE = '<?= SITE_ROOT ?>/api/public';

    // FAQ Toggle Functionality - FIXED
    document.querySelectorAll('.faq-item').forEach(item => {
        const questionDiv = item.querySelector('.faq-question');
        
        questionDiv.addEventListener('click', function(e) {
            e.stopPropagation();
            // Toggle active class on the clicked item
            const isActive = item.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(faq => {
                faq.classList.remove('active');
            });
            
            // If the clicked item wasn't active, open it
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });

    // Open first FAQ item by default
    const firstFaq = document.querySelector('.faq-item');
    if (firstFaq) {
        firstFaq.classList.add('active');
    }

    // Contact Form Submission
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const alertDiv = document.getElementById('alertMessage');

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-error' : 'alert-loading');
        alertDiv.innerHTML = `<div class="alert-message ${alertClass}" style="display:block;">${message}</div>`;
        
        // Auto hide after 5 seconds for success/error
        if (type !== 'loading') {
            setTimeout(() => {
                const alertMsg = alertDiv.querySelector('.alert-message');
                if (alertMsg) alertMsg.style.display = 'none';
            }, 5000);
        }
    }

    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const name = document.getElementById('nameField').value.trim();
        const email = document.getElementById('emailField').value.trim();
        const phone = document.getElementById('phoneField').value.trim();
        const subject = document.getElementById('subjectField').value;
        const message = document.getElementById('messageField').value.trim();

        // Validation
        if (!name || !email || !subject || !message) {
            showAlert('error', 'Please fill in all required fields.');
            return;
        }

        if (!email.includes('@') || !email.includes('.')) {
            showAlert('error', 'Please enter a valid email address.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
        showAlert('loading', 'Sending your message...');

        try {
            const response = await fetch(API_BASE + '/contact.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, email, phone, subject, message })
            });

            const data = await response.json();

            if (data.status === 'success') {
                showAlert('success', '✓ Your message has been sent successfully! We\'ll get back to you soon.');
                contactForm.reset();
            } else {
                showAlert('error', data.message || 'Failed to send message. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('error', 'Network error. Please check your connection and try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-send"></i> Send Message';
        }
    });
</script>

</body>
</html>