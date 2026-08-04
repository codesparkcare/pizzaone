<main class="contact-page">
    <!-- Contact Hero -->
    <section class="contact-hero">
        <div class="container">
            <h1>Get In Touch</h1>
            <p>Have questions or feedback? We'd love to hear from you!</p>
        </div>
    </section>

    <section class="contact-content section-padding">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Info -->
                <div class="contact-info-card">
                    <h2>Contact Information</h2>
                    <p class="subtitle">Reach out to us through any of these channels.</p>

                    <div class="info-items">
                        <div class="info-item">
                            <div class="icon-box">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="text-box">
                                <h3>Phone Numbers</h3>
                                <p><a href="tel:0134199456">01 34 19 94 56</a></p>
                                <p><a href="tel:0134141516">01 34 14 15 16</a></p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="icon-box">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="text-box">
                                <h3>Email Addresses</h3>
                                <p><a href="mailto:contact@example.com">contact@example.com</a></p>
                                <p><a href="mailto:info@example.com">info@example.com</a></p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="icon-box">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="text-box">
                                <h3>Our Location</h3>
                                <p>1. 11 Place de la Tolinette, 95400 Villiers-le-bel</p>
                                <p>2. Commercial des hauts de saint-nicolas, 93700 Le Plessis-Bouchard</p>
                            </div>
                        </div>
                    </div>

                    <div class="social-box">
                        <h3>Follow Us</h3>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form-card">
                    <h2>Send Us a Message</h2>
                    <form action="<?php echo base_url('welcome/send_message'); ?>" method="POST" class="styled-form">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="John Doe" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" placeholder="john@example.com" required>
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" placeholder="General Inquiry" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Send Message <i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15332.68652250284!2d-62.628906!3d17.133333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8c12140f7d457689%3A0xe549a16f27b9c9e8!2sCharlestown%2C%20St%20Kitts%20%26%20Nevis!5e0!3m2!1sen!2s!4v1652700000000!5m2!1sen!2s" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </section>
</main>
