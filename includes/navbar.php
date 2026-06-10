<!-- ======= HEADER WITH MEGA DROPDOWNS (IBEF STYLE) ======= -->
<header id="header" class="header d-flex align-items-center fixed-top" style="background: #ffffff !important; box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08); border-bottom: 1px solid #e8ecef;">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <img src="assets/img/industry-logo-20.png" alt="industry.co.zw Logo" style="max-height: 60px;">
      </a>

      <!-- Mobile Toggle Button -->
      <button class="mobile-nav-toggle d-xl-none" type="button" style="background: none; border: none; font-size: 28px; cursor: pointer; color: #1a2c3e;">
        <i class="bi bi-list"></i>
      </button>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>Home</a></li>
          
          <!-- Industries Mega Dropdown - Now links to industries.php -->
          <li class="dropdown has-mega">
            <a href="industries.php">Industries <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul class="mega-sub">
              <li class="mega-wrap">
                <div class="mega-grid">
                  <div class="mega-group">
                    <h6>Manufacturing & Engineering</h6>
                    <a href="industries.php?slug=auto">Auto</a>
                    <a href="industries.php?slug=construction">Construction</a>
                    <a href="industries.php?slug=manufacturing">Manufacturing</a>
                    <a href="industries.php?slug=energy-power">Energy & Power</a>
                  </div>
                  <div class="mega-group">
                    <h6>Resources & Agriculture</h6>
                    <a href="industries.php?slug=mining">Mining</a>
                    <a href="industries.php?slug=agriculture">Agriculture</a>
                    <a href="industries.php?slug=biotechnology">Biotechnology</a>
                  </div>
                  <div class="mega-group">
                    <h6>Services & Finance</h6>
                    <a href="industries.php?slug=banking-finance">Banking & Finance</a>
                    <a href="industries.php?slug=education">Education</a>
                    <a href="industries.php?slug=healthcare">Healthcare</a>
                    <a href="industries.php?slug=technology-ict">Technology & ICT</a>
                  </div>
                  <div class="mega-group">
                    <h6>Tourism & Transport</h6>
                    <a href="industries.php?slug=accommodation">Accommodation</a>
                    <a href="industries.php?slug=tourism-hospitality">Tourism & Hospitality</a>
                    <a href="industries.php?slug=transport-logistics">Transport & Logistics</a>
                  </div>
                </div>
                <div class="mega-footer" style="margin-top: 15px; padding-top: 12px; border-top: 1px solid #e8ecef; text-align: right;">
                  <a href="industries.php" style="color: #2e7d32; font-size: 12px; font-weight: 600; text-decoration: none;">View All Industries →</a>
                </div>
              </li>
            </ul>
          </li>
          
          <!-- Provinces Mega Dropdown -->
          <li class="dropdown has-mega">
            <a href="provinces.php">Provinces <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul class="mega-sub">
              <li class="mega-wrap">
                <div class="mega-grid">
                  <div class="mega-group">
                    <h6>Northern Region</h6>
                    <a href="province.php?slug=harare">Harare</a>
                    <a href="province.php?slug=mashonaland-central">Mashonaland Central</a>
                    <a href="province.php?slug=mashonaland-east">Mashonaland East</a>
                    <a href="province.php?slug=mashonaland-west">Mashonaland West</a>
                  </div>
                  <div class="mega-group">
                    <h6>Southern Region</h6>
                    <a href="province.php?slug=bulawayo">Bulawayo</a>
                    <a href="province.php?slug=matabeleland-north">Matabeleland North</a>
                    <a href="province.php?slug=matabeleland-south">Matabeleland South</a>
                    <a href="province.php?slug=midlands">Midlands</a>
                  </div>
                  <div class="mega-group">
                    <h6>Eastern Region</h6>
                    <a href="province.php?slug=manicaland">Manicaland</a>
                    <a href="province.php?slug=masvingo">Masvingo</a>
                  </div>
                </div>
                <div class="mega-footer" style="margin-top: 15px; padding-top: 12px; border-top: 1px solid #e8ecef; text-align: right;">
                  <a href="provinces.php" style="color: #2e7d32; font-size: 12px; font-weight: 600; text-decoration: none;">View All Provinces →</a>
                </div>
              </li>
            </ul>
          </li>
          
          <!-- Stakeholders Mega Dropdown -->
          <li class="dropdown has-mega">
            <a href="#">Stakeholders <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul class="mega-sub">
              <li class="mega-wrap">
                <div class="mega-grid">
                  <div class="mega-group">
                    <h6>CZI</h6>
                    <a href="stakeholder.php?org=CZI&section=directory">Membership Directory</a>
                    <a href="stakeholder.php?org=CZI&section=advertising">Advertising</a>
                    <a href="stakeholder.php?org=CZI&section=events">Events Calendar</a>
                    <a href="stakeholder.php?org=CZI&section=networking">Networking</a>
                  </div>
                  <div class="mega-group">
                    <h6>CIFOZ</h6>
                    <a href="stakeholder.php?org=CIFOZ&section=directory">Membership Directory</a>
                    <a href="stakeholder.php?org=CIFOZ&section=advertising">Advertising</a>
                    <a href="stakeholder.php?org=CIFOZ&section=events">Events Calendar</a>
                    <a href="stakeholder.php?org=CIFOZ&section=networking">Networking</a>
                  </div>
                </div>
              </li>
            </ul>
          </li>
          
          <li><a href="tenders.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'tenders.php') ? 'class="active"' : ''; ?>>Tenders</a></li>
          
          <!-- Exports Mega Dropdown -->
          <li class="dropdown has-mega">
            <a href="#">Exports <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul class="mega-sub" style="width: 280px;">
              <li class="mega-wrap">
                <div class="mega-grid" style="grid-template-columns: 1fr;">
                  <div class="mega-group">
                    <h6>Exports by Products</h6>
                    <a href="exports.php?category=Agriculture">Agricultural Products</a>
                    <a href="exports.php?category=Minerals">Minerals & Metals</a>
                    <a href="exports.php?category=Manufacturing">Manufactured Goods</a>
                    <a href="exports.php?category=Textiles">Textiles & Apparel</a>
                    <a href="exports.php?category=Horticulture">Horticulture</a>
                    <a href="exports.php" style="color: #2e7d32; font-weight: 600; margin-top: 8px; padding-top: 8px; border-top: 1px solid #e8ecef;">View All Products →</a>
                  </div>
                </div>
              </li>
            </ul>
          </li>
          
          <!-- More Mega Dropdown -->
          <li class="dropdown has-mega">
            <a href="#">More <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul class="mega-sub" style="width: 280px;">
              <li class="mega-wrap">
                <div class="mega-grid" style="grid-template-columns: 1fr;">
                  <div class="mega-group">
                    <h6>Media & Resources</h6>
                    <a href="events.php">Events</a>
                    <a href="gallery.php">Image Gallery</a>
                    <a href="videos.php">Video Gallery</a>
                  </div>
                  <div class="mega-group" style="margin-top: 10px;">
                    <h6>Information</h6>
                    <a href="about.php">About Us</a>
                    <a href="contact.php">Contact Us</a>
                  </div>
                </div>
              </li>
            </ul>
          </li>
          
          <li><a href="contact.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'class="active"' : ''; ?>>Contact</a></li>
        </ul>
      </nav>

      <a class="btn-getstarted" href="add-listing.php" style="background: #2e7d32; color: #ffffff; padding: 8px 20px; border-radius: 30px; font-weight: 600; font-size: 13px; text-decoration: none; transition: all 0.3s ease;">Add Listing</a>

    </div>
</header>

<style>
/* Desktop Navigation */
.navmenu ul {
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.navmenu ul li a {
    color: #1a2c3e;
    font-weight: 500;
    font-size: 14px;
    padding: 10px 15px;
    text-decoration: none;
    transition: all 0.2s;
    display: inline-block;
}

.navmenu ul li a:hover {
    color: #2e7d32;
}

.navmenu ul li a.active {
    color: #2e7d32;
}

/* Dropdown indicator */
.navmenu .dropdown > a .toggle-dropdown {
    font-size: 12px;
    margin-left: 4px;
    transition: transform 0.2s;
}

.navmenu .dropdown:hover > a .toggle-dropdown {
    transform: rotate(180deg);
}

/* Mega Menu */
.navmenu .dropdown.has-mega {
    position: relative;
}

.navmenu .mega-sub {
    background: #ffffff;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    border-top: 3px solid #2e7d32;
    border-radius: 8px;
    position: absolute;
    top: 100%;
    left: 0;
    width: 800px;
    padding: 25px;
    display: none;
    z-index: 1000;
}

.navmenu .dropdown.has-mega:first-of-type .mega-sub {
    left: -100px;
}

.navmenu .dropdown:hover .mega-sub {
    display: block;
}

.navmenu .mega-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}

.navmenu .mega-group h6 {
    color: #2e7d32;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    border-bottom: 1px solid #e8ecef;
    padding-bottom: 8px;
}

.navmenu .mega-group a {
    color: #4a5568;
    font-size: 13px;
    padding: 6px 0;
    display: block;
    text-decoration: none;
    transition: all 0.2s;
}

.navmenu .mega-group a:hover {
    color: #2e7d32;
    padding-left: 5px;
}

.mega-footer a:hover {
    padding-left: 0 !important;
}

/* Mobile Navigation */
@media (max-width: 1200px) {
    .navmenu {
        position: fixed;
        top: 0;
        left: -100%;
        width: 85%;
        max-width: 320px;
        height: 100vh;
        background: #ffffff;
        box-shadow: 2px 0 20px rgba(0,0,0,0.1);
        transition: left 0.3s ease;
        z-index: 9999;
        padding: 80px 20px 30px;
        overflow-y: auto;
    }
    
    .navmenu.mobile-nav-active {
        left: 0;
    }
    
    .navmenu ul {
        flex-direction: column;
        align-items: stretch;
        gap: 0;
    }
    
    .navmenu ul li a {
        display: block;
        padding: 14px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 15px;
    }
    
    /* Mobile dropdown handling */
    .navmenu .dropdown.has-mega {
        position: static;
    }
    
    .navmenu .dropdown.has-mega > a {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .navmenu .dropdown.has-mega.open > a .toggle-dropdown {
        transform: rotate(180deg);
    }
    
    .navmenu .mega-sub {
        position: static !important;
        width: 100% !important;
        box-shadow: none !important;
        border-top: none !important;
        padding: 0 0 0 15px !important;
        margin: 0 !important;
        background: #f8f9fa !important;
        border-radius: 8px !important;
        display: none !important;
        left: auto !important;
    }
    
    .navmenu .dropdown.has-mega.open .mega-sub {
        display: block !important;
    }
    
    .navmenu .mega-grid {
        grid-template-columns: 1fr !important;
        gap: 15px !important;
        padding: 10px 0;
    }
    
    .navmenu .mega-group h6 {
        margin-top: 10px;
        margin-bottom: 5px;
        font-size: 12px;
    }
    
    .navmenu .mega-group a {
        padding: 8px 0 8px 10px;
        font-size: 13px;
    }
    
    .mega-footer {
        padding: 10px 0 0 !important;
        margin-top: 10px !important;
    }
    
    .mobile-nav-toggle {
        display: flex !important;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }
    
    .btn-getstarted {
        margin-right: 10px;
        padding: 6px 15px !important;
        font-size: 12px !important;
    }
    
    .logo img {
        max-height: 45px !important;
    }
    
    body {
        padding-top: 65px;
    }
    
    /* Overlay */
    .menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9998;
        display: none;
    }
    
    .menu-overlay.active {
        display: block;
    }
    
    body.menu-open {
        overflow: hidden;
    }
}

@media (min-width: 1201px) {
    .mobile-nav-toggle {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .btn-getstarted {
        padding: 5px 12px !important;
        font-size: 11px !important;
    }
}

/* Small desktop adjustment */
@media (min-width: 1201px) and (max-width: 1400px) {
    .navmenu .mega-sub {
        width: 720px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-nav-toggle');
    const navmenu = document.querySelector('.navmenu');
    const body = document.body;
    
    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'menu-overlay';
    body.appendChild(overlay);
    
    if (mobileToggle && navmenu) {
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            navmenu.classList.toggle('mobile-nav-active');
            overlay.classList.toggle('active');
            body.classList.toggle('menu-open');
            
            const icon = mobileToggle.querySelector('i');
            if (navmenu.classList.contains('mobile-nav-active')) {
                icon.className = 'bi bi-x';
            } else {
                icon.className = 'bi bi-list';
            }
        });
    }
    
    // Close menu when clicking overlay
    overlay.addEventListener('click', function() {
        navmenu.classList.remove('mobile-nav-active');
        overlay.classList.remove('active');
        body.classList.remove('menu-open');
        if (mobileToggle) {
            const icon = mobileToggle.querySelector('i');
            icon.className = 'bi bi-list';
        }
    });
    
    // Handle dropdown toggles on mobile
    const dropdowns = document.querySelectorAll('.dropdown.has-mega > a');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            if (window.innerWidth <= 1200) {
                e.preventDefault();
                const parent = this.closest('.dropdown.has-mega');
                const wasOpen = parent.classList.contains('open');
                
                // Close all other dropdowns
                document.querySelectorAll('.dropdown.has-mega').forEach(d => {
                    d.classList.remove('open');
                });
                
                // Toggle current
                if (!wasOpen) {
                    parent.classList.add('open');
                }
            }
        });
    });
    
    // Close menu when clicking a link (except dropdown toggles)
    const links = document.querySelectorAll('.navmenu a');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            // Check if it's a dropdown toggle on mobile
            const isDropdownToggle = this.closest('.dropdown.has-mega') && 
                                     this.getAttribute('href') === '#' && 
                                     window.innerWidth <= 1200;
            
            if (!isDropdownToggle && window.innerWidth <= 1200) {
                navmenu.classList.remove('mobile-nav-active');
                overlay.classList.remove('active');
                body.classList.remove('menu-open');
                if (mobileToggle) {
                    const icon = mobileToggle.querySelector('i');
                    icon.className = 'bi bi-list';
                }
            }
        });
    });
    
    // Handle resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1200) {
            navmenu.classList.remove('mobile-nav-active');
            overlay.classList.remove('active');
            body.classList.remove('menu-open');
            // Reset dropdowns
            document.querySelectorAll('.dropdown.has-mega').forEach(d => {
                d.classList.remove('open');
            });
            if (mobileToggle) {
                const icon = mobileToggle.querySelector('i');
                icon.className = 'bi bi-list';
            }
        }
    });
});
</script>