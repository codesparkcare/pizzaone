<style>
.about-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
    font-family: 'Poppins', sans-serif;
}
.about-section-1 {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 40px;
    margin-bottom: 60px;
}
.about-image {
    flex: 1;
    min-width: 300px;
}
.about-image img {
    width: 100%;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.about-content {
    flex: 1;
    min-width: 300px;
}
.about-content h2 {
    font-family: 'Lobster', cursive;
    color: var(--primary, #e74c3c);
    font-size: 2.5rem;
    margin-bottom: 20px;
}
.about-content p {
    color: #555;
    line-height: 1.8;
    font-size: 1.1rem;
    margin-bottom: 15px;
}

.about-section-2 {
    background: #f8f9fa;
    padding: 40px;
    border-radius: 10px;
    text-align: center;
}
.about-section-2 h2 {
    font-family: 'Lobster', cursive;
    color: var(--secondary, #2c3e50);
    font-size: 2.5rem;
    margin-bottom: 30px;
}
.branches-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
}
.branch-card {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    flex: 1;
    min-width: 250px;
    max-width: 400px;
    text-align: left;
}
.branch-card i {
    font-size: 2rem;
    color: var(--primary, #e74c3c);
    margin-bottom: 15px;
}
.branch-card h3 {
    margin-top: 0;
    margin-bottom: 10px;
    color: #333;
    font-size: 1.3rem;
}
.branch-card p {
    color: #666;
    line-height: 1.6;
    margin: 0;
}
@media (max-width: 768px) {
    .about-section-1 {
        flex-direction: column;
    }
}
</style>

<div class="about-container">
    <!-- Section 1 -->
    <div class="about-section-1">
        <div class="about-image">
            <img src="<?php echo base_url('assets/images/about-pizza.jpg'); ?>" alt="About Pizza One" onerror="this.src='https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';">
        </div>
        <div class="about-content">
            <h2>Our Story</h2>
            <p>Welcome to Pizza One, where passion meets authenticity! We pride ourselves on delivering the true taste of Italian pizza, crafted with love, fresh ingredients, and traditional recipes.</p>
            <p>Every pizza we make is a testament to our dedication to quality. From our hand-tossed dough to our rich, flavorful sauces and premium toppings, we ensure every bite is an unforgettable experience.</p>
        </div>
    </div>

    <!-- Section 2 -->
    <div class="about-section-2">
        <h2>Our Branches</h2>
        <div class="branches-container">
            <div class="branch-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Villiers Le Bel</h3>
                <p>11 Place de la Tolinette,<br>95400 Villiers Le Bel</p>
                <p style="margin-top: 10px; font-weight: bold;"><i class="fas fa-phone"></i> 01 34 19 94 56</p>
            </div>
            <div class="branch-card">
                <i class="fas fa-store"></i>
                <h3>Le Plessis-Bouchard</h3>
                <p>Commercial des Hauts de Saint-Nicolas<br>93700 Le Plessis-Bouchard</p>
                <p style="margin-top: 10px; font-weight: bold;"><i class="fas fa-phone"></i> 01 34 14 15 16</p>
            </div>
        </div>
    </div>
</div>
