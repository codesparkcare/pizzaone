<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-content">
            <!-- Brand Column -->
            <div class="footer-section footer-brand-col">
                <div class="footer-logo">
                    <a href="<?php echo base_url(); ?>">
                        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Pizza One Logo">
                    </a>
                </div>
                <p class="brand-tagline">
                    Des pizzas artisanales et authentiques préparées chaque jour avec des ingrédients frais de qualité.
                    Sélectionnez votre magasin et commandez en ligne !
                </p>
                <div class="social-icons">
                    <a href="#" class="social-link facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link instagram" aria-label="Instagram"><i
                            class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link twitter" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link whatsapp" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- Shop 1 Card: Villiers-le-bel (Red Theme) -->
            <div class="footer-section footer-shop-card shop-vlb-card">
                <div class="shop-card-header">
                    <div class="shop-icon-wrapper vlb-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <span class="shop-badge vlb-badge">Magasin 1</span>
                        <h3 class="shop-title">Villiers-le-bel</h3>
                    </div>
                </div>

                <div class="shop-card-body">
                    <div class="info-row">
                        <i class="fas fa-map-marker-alt icon-vlb"></i>
                        <span>11 Place de la Tolinette, 95400 Villiers Le Bel</span>
                    </div>

                    <div class="info-row phone-row">
                        <i class="fas fa-phone-alt icon-vlb"></i>
                        <a href="tel:0134199456" class="phone-link">01 34 19 94 56</a>
                    </div>

                    <div class="action-row">
                        <a href="tel:0134199456" class="btn-call-action btn-vlb-call">
                            <i class="fas fa-phone"></i> Appelez maintenant
                        </a>
                    </div>

                    <div class="hours-block">
                        <h4 class="hours-title"><i class="fas fa-clock"></i> Horaires d'ouverture</h4>
                        <div class="hour-item">
                            <span class="day-label">Samedi - Jeudi</span>
                            <span class="time-label">11h00 à 23h00</span>
                        </div>
                        <div class="hour-item">
                            <span class="day-label">Vendredi</span>
                            <span class="time-label">16h00 à 23h00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shop 2 Card: Le Plessis-Bouchard (Blue Theme) -->
            <div class="footer-section footer-shop-card shop-lpb-card">
                <div class="shop-card-header">
                    <div class="shop-icon-wrapper lpb-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <span class="shop-badge lpb-badge">Magasin 2</span>
                        <h3 class="shop-title">Le Plessis-Bouchard</h3>
                    </div>
                </div>

                <div class="shop-card-body">
                    <div class="info-row">
                        <i class="fas fa-map-marker-alt icon-lpb"></i>
                        <span>Commercial des Hauts de Saint-Nicolas, 95130 Le Plessis-Bouchard</span>
                    </div>

                    <div class="info-row phone-row">
                        <i class="fas fa-phone-alt icon-lpb"></i>
                        <a href="tel:0134141516" class="phone-link">01 34 14 15 16</a>
                    </div>

                    <div class="action-row">
                        <a href="tel:0134141516" class="btn-call-action btn-lpb-call">
                            <i class="fas fa-phone"></i> Appelez maintenant
                        </a>
                    </div>

                    <div class="hours-block">
                        <h4 class="hours-title"><i class="fas fa-clock"></i> Horaires d'ouverture</h4>
                        <div class="hour-item">
                            <span class="day-label">Samedi - Jeudi</span>
                            <span class="time-label">11h00 à 14h00 et 18h00 à 23h00</span>
                        </div>
                        <div class="hour-item">
                            <span class="day-label">Vendredi</span>
                            <span class="time-label">18h00 à 23h00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>&copy; <?php echo date('Y'); ?> <a href="https://www.codespark.online" target="_blank">CODESPARK</a>
                    Tous droits réservés.</p>
                <div class="footer-bottom-links">
                    <a href="#">Politique de confidentialité</a>
                    <a href="#">Conditions d'utilisation</a>
                </div>
                <p class="crafted-by">Fait avec <i class="fas fa-heart"></i> pour les amateurs de pizza</p>
            </div>
        </div>
    </div>
</footer>

<a href="tel:+33134199456" class="floating-call-btn" title="Appelez-nous">
    <i class="fas fa-phone-alt"></i>
    <span class="btn-text">+33 1 34 19 94 56</span>
</a>

<script type="text/javascript">
    window.currentLang = '<?php echo current_lang(); ?>';
    window.t = function (fr, en) {
        return window.currentLang === 'en' ? (en || fr) : fr;
    };

    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'fr',
            includedLanguages: 'fr,en',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }

    function changeLanguage(langCode) {
        var activeLangDisplay = document.querySelector('.active-lang');
        if (activeLangDisplay) {
            activeLangDisplay.innerText = langCode.toUpperCase();
        }

        document.cookie = "site_lang=" + langCode + "; path=/; max-age=31536000;";

        var domain = window.location.hostname;
        if (langCode === 'fr') {
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + domain + ";";
            document.cookie = "googtrans=/fr/fr; path=/;";
            document.cookie = "googtrans=/fr/fr; path=/; domain=" + domain + ";";
        } else {
            document.cookie = "googtrans=/fr/" + langCode + "; path=/;";
            document.cookie = "googtrans=/fr/" + langCode + "; path=/; domain=" + domain + ";";
        }

        var langDropdown = document.getElementById('langDropdown');
        if (langDropdown) {
            langDropdown.classList.remove('show');
        }

        fetch('<?php echo base_url("language/switch_lang/"); ?>' + langCode)
            .then(function () { location.reload(); })
            .catch(function () { location.reload(); });
    }

    // Auto-detect existing translation on page load
    window.addEventListener('load', function () {
        setTimeout(function () {
            var selectField = document.querySelector('select.goog-te-combo');
            var activeLangDisplay = document.querySelector('.active-lang');
            if (selectField && selectField.value) {
                if (activeLangDisplay) {
                    activeLangDisplay.innerText = selectField.value.toUpperCase();
                }
            } else {
                if (activeLangDisplay) {
                    activeLangDisplay.innerText = 'FR';
                }
            }
        }, 800);
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
                <p>${window.t('Chargement des détails...', 'Loading delicious details...')}</p>
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
                modalBody.innerHTML = `<p style="padding: 2rem; text-align: center; color: red;">${window.t('Erreur de chargement du produit.', 'Error loading product.')}</p>`;
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
            <h3 class="custom-alert-title"><?php echo t('Notice', 'Notice'); ?></h3>
        </div>
        <div class="custom-alert-body">
            <p id="customAlertMessage"></p>
        </div>
        <div class="custom-alert-footer">
            <button class="custom-alert-btn" onclick="closeCustomAlert()"><?php echo t('OK', 'OK'); ?></button>
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
            titleElem.textContent = window.t('Oups !', 'Oops!');
        } else if (isSuccess) {
            iconElem.classList.add('success');
            iconElem.innerHTML = '<i class="fas fa-check-circle"></i>';
            titleElem.textContent = window.t('Succès !', 'Success!');
        } else {
            iconElem.innerHTML = '<i class="fas fa-info-circle"></i>';
            titleElem.textContent = window.t('Notice', 'Notice');
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
    window.addEventListener('load', function () {
        const loader = document.getElementById('pizza-loader');
        if (loader) {
            loader.classList.add('fade-out');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500); // Matches CSS transition duration
        }
    });

    // Wishlist Toggle
    function toggleWishlist(productId, btnElement) {
        fetch('<?php echo base_url("user/toggle_wishlist/"); ?>' + productId)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    alert(data.message);
                    window.location.href = '<?php echo base_url("user/login"); ?>';
                } else {
                    // Update badge count
                    let badge = document.getElementById('wishlistBadge');
                    if (badge) {
                        badge.textContent = data.wishlist_count;
                        if (data.wishlist_count > 0) {
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }

                    if (data.status === 'added') {
                        let icon = btnElement.querySelector('i');
                        if (icon) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            icon.style.color = '#ff4757';
                        }
                    } else if (data.status === 'removed') {
                        let icon = btnElement.querySelector('i');
                        if (icon) {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            icon.style.color = '#fff';
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error toggling wishlist:', error);
            });
    }
</script>

<script type="text/javascript"
    src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>

</html>