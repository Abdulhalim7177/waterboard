<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katsina State Water Baord</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/swiper@8/swiper-bundle.min.css" rel="stylesheet">
    <style>
        .swiper-slide img {
            height: 500px;
            object-fit: cover;
            width: 100%;
        }
        .swiper {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .feature-card, .benefit-card {
            transition: transform 0.3s ease;
        }
        .feature-card:hover, .benefit-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navigation Bar -->
    <nav class="bg-gray-100 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img alt="Logo" src="assets/media/logos/logo.png" class="h-10 md:h-10 me-2" />
                    <span class="text-2xl font-bold text-blue-600">KTSWB</span>
                </div>
                <div class="flex items-center">
                    <div class="hidden md:block">
                        <a href="/customer/login" class="px-3 py-2 text-gray-700 hover:text-blue-600">Customer Login</a>
                    </div>
                    <div class="md:hidden">
                        <button id="mobile-menu-button" type="button" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden">
            <a href="/customer/login" class="block px-4 py-2 text-gray-700 hover:text-blue-600 bg-white border-t">Customer Login</a>
        </div>
    </nav>

    <!-- Hero Section with Slider -->
    <section class="py-10">
        <div class="container mx-auto">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="https://media.istockphoto.com/id/1325980429/photo/aerial-view-of-drinking-water-treatment-plants-for-big-city-from-water-management.jpg?s=1024x1024&w=is&k=20&c=sRoZxipJHqj6Byqmq6qZfJPeg5dApbusKrXxPj7eupY=" alt="Water Management">
                        <div class="absolute bottom-10 left-10 text-white bg-black bg-opacity-50 p-4 rounded">
                            <h3 class="text-2xl md:text-3xl font-bold">Efficient Water Management</h3>
                            <p class="text-lg md:text-xl">Streamline customer management, billing, and payments with our automated system.</p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="https://media.istockphoto.com/id/180538181/photo/industrial-zone-steel-pipelines-and-equipment.jpg?s=1024x1024&w=is&k=20&c=WHnFPtPgcSoKMkbS9qhzJAAMme4IdDZ1WwmtEsr7YLc=" alt="Secure Payments">
                        <div class="absolute bottom-10 left-10 text-white bg-black bg-opacity-50 p-4 rounded">
                            <h3 class="text-2xl md:text-3xl font-bold">Secure Payment Processing</h3>
                            <p class="text-lg md:text-xl">Pay bills seamlessly with NABRoll integration and vendor support.</p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="https://media.istockphoto.com/id/1369464717/photo/engineer-take-water-from-wastewater-treatment-pond-to-check-the-quality-of-the-water-after.jpg?s=2048x2048&w=is&k=20&c=5D6RI05gGhQqUwrea9_gjOQBWw6WlaRtH7SRYqZHCfI=" alt="Customer Support">
                        <div class="absolute bottom-10 left-10 text-white bg-black bg-opacity-50 p-4 rounded">
                            <h3 class="text-2xl md:text-3xl font-bold">Robust Customer Support</h3>

                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-700 mb-8">Our Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg feature-card">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Customer Management</h3>
                    <p class="text-gray-600">Manage customer data, billing IDs, and geographic locations with ease.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg feature-card">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Billing & Payments</h3>
                    <p class="text-gray-600">Automated billing with metered/flat rates and secure payment processing.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg feature-card">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Customer Support</h3>
                    <p class="text-gray-600">Dedicated support team for customer inquiries and service requests.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-700 mb-8">Why Choose Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg benefit-card">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Automation</h3>
                    <p class="text-gray-600">Reduce manual processes with our fully automated system for billing and customer management.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg benefit-card">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Transparency</h3>
                    <p class="text-gray-600">Track all actions with audit trails and ensure clear, accurate billing for customers.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg benefit-card">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Scalability</h3>
                    <p class="text-gray-600">Designed to grow with your needs, supporting GIS, SCADA, and more in the future.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>© 2025 Katsina State Water Baord. All rights reserved.</p>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script>
        // Swiper initialization
        const swiper = new Swiper('.swiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-prev',
                prevEl: '.swiper-button-next',
            },
            autoplay: {
                delay: 5000,
            },
        });

        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function () {
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            menuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>