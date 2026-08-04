<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section about">
                <div class="footer-logo">
                    <a href="<?php echo base_url(); ?>">
                        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Pizza One Logo">
                    </a>
                </div>
                <div class="social-icons">
                    <a href="#" class="social-link facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link whatsapp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="footer-section contact-item">
                <div class="section-header">
                    <i class="fas fa-map-marked-alt"></i>
                    <h3>Our Address</h3>
                </div>
                <div class="contact-details">
                    <p>1. 11 Place de la Tolinette, 95400 Villiers-le-bel</p>
                    <p>2. Commercial des hauts de saint-nicolas, 93700 Le Plessis-Bouchard</p>
                    <a href="https://maps.google.com" target="_blank" class="map-link">View on Map <i
                            class="fas fa-external-link-alt"></i></a>
                </div>
            </div>

            <div class="footer-section contact-item">
                <div class="section-header">
                    <i class="fas fa-utensils"></i>
                    <h3>Book a Table</h3>
                </div>
                <div class="contact-details">
                    <p class="service-desc">Call us to place an order or book a table.</p>
                    <p class="phone-number"><i class="fas fa-phone-alt"></i> <a href="tel:0134199456"
                            style="color: inherit; text-decoration: none;">01 34 19 94 56</a></p>
                    <p class="phone-number"><i class="fas fa-phone-alt"></i> <a href="tel:0134141516"
                            style="color: inherit; text-decoration: none;">01 34 14 15 16</a></p>
                    <a href="tel:0134199456" class="btn-footer-call">Call Now</a>
                </div>
            </div>

            <div class="footer-section contact-item">
                <div class="section-header">
                    <i class="fas fa-clock"></i>
                    <h3>Opening Hours</h3>
                </div>
                <div class="opening-details">
                    <div class="hour-row">
                        <span>Monday – Friday:</span>
                        <span class="time">8am – 4pm</span>
                    </div>
                    <div class="hour-row">
                        <span>Saturday:</span>
                        <span class="time">9am – 5pm</span>
                    </div>
                    <div class="hour-row closed">
                        <span>Sunday:</span>
                        <span class="time">Closed</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>&copy; <?php echo date('Y'); ?> <a href="https://www.codespark.online" target="_blank">CODESPARK</a>
                    All
                    Rights Reserved.</p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
                <p class="crafted-by">Crafted with <i class="fas fa-heart"></i> for Pizza Lovers</p>
            </div>
        </div>
    </div>
</footer>

<a href="tel:+33134199456" class="floating-call-btn" title="Call Us">
    <i class="fas fa-phone-alt"></i>
    <span class="btn-text">+33 1 34 19 94 56</span>
</a>

<!-- Google Translate Scripts -->
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,fr',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }

    var langRetries = 0;
    function changeLanguage(langCode) {
        // Find the Google Translate select element
        var selectField = document.querySelector('select.goog-te-combo');

        if (selectField) {
            // Set the value and trigger change event
            selectField.value = langCode;

            // Trigger both 'change' and 'click' events for better compatibility
            selectField.dispatchEvent(new Event('change', { bubbles: true }));

            // Update the button text
            var activeLangDisplay = document.querySelector('.active-lang');
            if (activeLangDisplay) {
                activeLangDisplay.innerText = langCode.toUpperCase();
            }

            // Close dropdown if open
            var langDropdown = document.getElementById('langDropdown');
            if (langDropdown) {
                langDropdown.classList.remove('show');
            }
            langRetries = 0;
        } else {
            if (langRetries < 5) {
                langRetries++;
                console.log("Translator still loading... retrying for: " + langCode);
                setTimeout(function () {
                    changeLanguage(langCode);
                }, 500);
            } else {
                console.log("Translator failed to load. Setting cookie fallback.");
                document.cookie = "googtrans=/en/" + langCode + "; path=/;";
                document.cookie = "googtrans=/en/" + langCode + "; path=/; domain=" + window.location.hostname + ";";
                location.reload();
            }
        }
    }

    // Optional: Auto-detect existing translation on page load
    window.addEventListener('load', function () {
        setTimeout(function () {
            var selectField = document.querySelector('select.goog-te-combo');
            if (selectField && selectField.value) {
                var activeLangDisplay = document.querySelector('.active-lang');
                if (activeLangDisplay) {
                    activeLangDisplay.innerText = selectField.value.toUpperCase();
                }
            }
        }, 1000);
    });
</script>
<!-- Product Quick View Modal -->
<div id="productQuickView" class="product-modal">
    <div class="product-modal-content">
        <span class="close-modal" onclick="closeProductModal()">&times;</span>
        <div class="product-modal-body" id="modalBody">
            <!-- Content loaded via JS -->
            <div class="modal-loading">
                <div class="spinner"></div>
                <p>Loading delicious details...</p>
            </div>
        </div>
    </div>
</div>

<!-- Swiper Slider Script -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    function openProductModal(productId) {
        const modal = document.getElementById('productQuickView');
        const modalBody = document.getElementById('modalBody');
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';

        modalBody.innerHTML = `
            <div class="modal-loading">
                <div class="spinner"></div>
                <p>Loading delicious details...</p>
            </div>
        `;

        fetch('<?php echo base_url('cart/quick_view/'); ?>' + productId, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    modalBody.innerHTML = res.html;

                    // Execute scripts embedded in the HTML response
                    const scripts = modalBody.getElementsByTagName('script');
                    for (let i = 0; i < scripts.length; i++) {
                        const newScript = document.createElement('script');
                        newScript.text = scripts[i].text;
                        document.body.appendChild(newScript).parentNode.removeChild(newScript);
                    }
                } else {
                    modalBody.innerHTML = `<p style="padding: 2rem; text-align: center; color: red;">${res.message}</p>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = `<p style="padding: 2rem; text-align: center; color: red;">Error loading product.</p>`;
            });
    }

    function closeProductModal() {
        document.getElementById('productQuickView').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function (event) {
        const modal = document.getElementById('productQuickView');
        if (event.target == modal) {
            closeProductModal();
        }
    }
</script>

<!-- Global Custom Alert Modal -->
<div id="customAlertModal" class="custom-alert-overlay" style="display: none;">
    <div class="custom-alert-content">
        <div class="custom-alert-header">
            <span class="custom-alert-icon"><i class="fas fa-info-circle"></i></span>
            <h3 class="custom-alert-title">Notice</h3>
        </div>
        <div class="custom-alert-body">
            <p id="customAlertMessage"></p>
        </div>
        <div class="custom-alert-footer">
            <button class="custom-alert-btn" onclick="closeCustomAlert()">OK</button>
        </div>
    </div>
</div>

<style>
    .custom-alert-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999999;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .custom-alert-overlay.show {
        opacity: 1;
    }

    .custom-alert-content {
        background: #fff;
        border-radius: 16px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        transform: scale(0.9);
        transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(0, 0, 0, 0.05);
        font-family: 'Poppins', sans-serif;
    }

    .custom-alert-overlay.show .custom-alert-content {
        transform: scale(1);
    }

    .custom-alert-header {
        padding: 1.5rem 1.5rem 0.5rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .custom-alert-icon {
        font-size: 1.6rem;
        color: #e67e22;
        /* primary orange */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-alert-icon.error {
        color: #e74c3c;
    }

    .custom-alert-icon.success {
        color: #2ecc71;
    }

    .custom-alert-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .custom-alert-body {
        padding: 0.5rem 1.5rem 1.5rem 1.5rem;
        color: #555;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .custom-alert-footer {
        padding: 1rem 1.5rem;
        background: #fdfefe;
        border-top: 1px solid #f2f4f4;
        display: flex;
        justify-content: flex-end;
    }

    .custom-alert-btn {
        background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        color: #fff;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.9rem;
        transition: transform 0.1s, box-shadow 0.2s;
    }

    .custom-alert-btn:hover {
        box-shadow: 0 4px 12px rgba(230, 126, 34, 0.2);
    }

    .custom-alert-btn:active {
        transform: scale(0.97);
    }
</style>

<script>
    // Global alert override
    window.alert = function (message) {
        showCustomAlert(message);
    };

    function showCustomAlert(message) {
        const modal = document.getElementById('customAlertModal');
        const msgElem = document.getElementById('customAlertMessage');
        const iconElem = document.querySelector('.custom-alert-icon');
        const titleElem = document.querySelector('.custom-alert-title');

        // Auto-detect message type for nicer icons/titles
        let msgLower = message.toLowerCase();
        let isError = msgLower.includes('error') || msgLower.includes('failed') || msgLower.includes('cannot') || msgLower.includes('maximum') || msgLower.includes('select a size') || msgLower.includes('please select');
        let isSuccess = msgLower.includes('success') || msgLower.includes('added') || msgLower.includes('confirmed') || msgLower.includes('thank');

        iconElem.className = 'custom-alert-icon';
        if (isError) {
            iconElem.classList.add('error');
            iconElem.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
            titleElem.textContent = 'Oops!';
        } else if (isSuccess) {
            iconElem.classList.add('success');
            iconElem.innerHTML = '<i class="fas fa-check-circle"></i>';
            titleElem.textContent = 'Success!';
        } else {
            iconElem.innerHTML = '<i class="fas fa-info-circle"></i>';
            titleElem.textContent = 'Notice';
        }

        msgElem.textContent = message;
        modal.style.display = 'flex';
        // Trigger layout before adding show class for transition
        void modal.offsetWidth;
        modal.classList.add('show');
    }

    function closeCustomAlert() {
        const modal = document.getElementById('customAlertModal');
        modal.classList.remove('show');
        // Wait for animation to finish
        setTimeout(() => {
            modal.style.display = 'none';
        }, 250);
    }
</script>

<script>
    // Pizza Loader Script
    window.addEventListener('load', function() {
        const loader = document.getElementById('pizza-loader');
        if (loader) {
            loader.classList.add('fade-out');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500); // Matches CSS transition duration
        }
    });
</script>

<script type="text/javascript"
    src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>

</html>