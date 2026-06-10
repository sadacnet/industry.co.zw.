# Codebase Overview: Industry in Zimbabwe (industry.co.zw)

## Project Description
Industry in Zimbabwe is a comprehensive industrial portal and business directory. It serves as a central hub for various industry sectors in Zimbabwe, providing a platform for companies to be discovered, showcasing premium partners, and listing business opportunities like tenders and events. It also specifically hosts portals for major stakeholders like **CZI** (Confederation of Zimbabwe Industries) and **CIFOZ** (Construction Industry Federation of Zimbabwe).

## Technology Stack
- **Backend**: PHP (mix of procedural and some OOP)
- **Database**: MySQL/MariaDB (accessed via PDO)
- **Frontend**: HTML5, CSS3, JavaScript
- **CSS Framework**: Bootstrap 5
- **JS Libraries**:
  - AOS (Animate on Scroll)
  - GLightbox (Lightboxes)
  - Swiper (Touch sliders)
  - Isotope (Filtering/Sorting)
  - Waypoints

## Core Features
### 1. Public Directory
- **Industry Browsing**: Companies categorized by sector (Mining, Agriculture, etc.) with premium showcases.
- **Province Browsing**: Regional categorization of businesses across Zimbabwe's 10 provinces.
- **Search & Filtering**: Real-time filtering by keyword and province using the public API.

### 2. Business Opportunities
- **Tenders**: A searchable listing of active business tenders with downloadable documents.
- **Exports**: A showcase of Zimbabwean export products with RFQ (Request for Quote) functionality.
- **Events**: A timeline-based listing of industry conferences and networking events.

### 3. Stakeholder Portals
- Dedicated pages for **CZI** and **CIFOZ** featuring member directories, events, and networking information.
- CIFOZ specifically includes a complex category hierarchy (General Contractors, Sub-Contractors, etc.).

### 4. Admin Management
- A protected dashboard for administrators to manage all listings.
- **CSV Import**: A specialized tool to bulk-import company data from CSV files.
- **API Management**: CRUD operations for members, events, and tenders.

## Directory Structure
- `/admin`: Admin panel files and admin-specific API.
- `/api`: Public and configuration API files.
- `/assets`: CSS, JS, images, and vendor libraries.
- `/database`: SQL schema and seed data.
- `/includes`: Reusable UI components like `navbar.php` and `footer.php`.
- `/uploads`: Storage for company logos, event posters, and tender documents.

## Data Flow
1. **Initial Load**: PHP renders the base page and includes header/footer.
2. **Dynamic Content**: JavaScript on the client side fetches data from the `/api/public/` endpoints in JSON format.
3. **Rendering**: The UI is dynamically updated using JavaScript templates (Template Literals) based on the API response.
4. **Admin Updates**: The admin panel interacts with `/admin/api/` to perform secure database updates.

## Database Schema Highlights
- `industries`: Defines the sectors.
- `provinces`: Defines the regions.
- `companies`: The central table for all business listings, linked to industries and provinces.
- `tenders`, `events`, `exports`: Tables for specific business data types.
- `admin_users`: Authentication for the management portal.
- `cifoz_categories`, `company_cifoz_categories`: Specialized category hierarchy for CIFOZ.
- `industry_showcase`: Links companies to specific industry landing page highlights.
- `showcase_*`: Specialized tables for premium content (Banners, Flyers, Gallery, Posters, Videos).
- `industry_news`: Stores industry-specific news articles and updates.
