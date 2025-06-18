<!DOCTYPE html>
<!-- Declares the document type as HTML5 -->

<html>
<head>
    <title>Talent Catalogue</title>
    <!-- Sets the title shown in the browser tab -->

    <link rel="stylesheet" href="css/style.css">
    <!-- Links an external CSS file for styling the page -->

    <script src="js/filter.js" defer></script>
    <!-- Links an external JavaScript file that contains filtering logic.
         The "defer" attribute makes sure the script runs after the HTML is loaded -->
</head>

<body>
    <!-- Start of the body content -->

    <header>
        <!-- Header section contains the logo, navigation, and filter tabs -->

        <div class="logo">LOGO</div>
        <div class="name">CATALOGUE</div>
        <!-- Placeholder for the site's logo -->

        <nav>
            <!-- Navigation bar -->

            <input type="text" id="search" placeholder="Search...">
            <!-- Search input field with ID for JS targeting -->

            <button onclick="searchTalent()">🔍</button>
            <!-- Button that triggers the searchTalent() JavaScript function on click -->

            <span class="user-icon">👤</span>
            <!-- Icon or placeholder for user profile (static text/icon for now) -->
        </nav>

        <div class="tabs">
            <!-- Category filter buttons -->

            <button onclick="filterCategory('all')">All</button>
            <!-- Filters to show all items -->

            <button onclick="filterCategory('music')">Music</button>
            <!-- Filters to show only Music category -->

            <button onclick="filterCategory('technology')">Technology</button>
            <!-- Filters to show only Technology category -->

            <button onclick="filterCategory('art')">Art</button>
            <!-- Filters to show only Art category -->
        </div>
    </header>

    <section class="hero">
        <!-- Hero section with main message -->

        <h1>Discover all our talents</h1>
        <!-- Large heading encouraging users to explore talents -->
    </section>

    <section class="talent-section" id="music">
        <!-- Section for Music talents -->

        <h2>Music</h2>
        <!-- Title for this category -->

        <div class="grid">
            <!-- Container for talent cards displayed in a grid -->

            <?php for ($i = 1; $i <= 5; $i++): ?>
            <!-- PHP loop to generate 5 music talent cards -->

            <div class="talent-card category-music">
                <!-- Single card styled as music category -->

                <img src="images/song<?= $i ?>.jpg" alt="Song <?= $i ?>">
                <!-- Image path uses PHP to dynamically insert the number (e.g., song1.jpg) -->

                <p>Song <?= $i ?><br><small>Description</small></p>
                <!-- Text showing song number and a small description -->
            </div>

            <?php endfor; ?>
            <!-- End of PHP loop -->
        </div>
    </section>

    <section class="talent-section" id="technology">
        <!-- Section for Technology talents -->

        <h2>Technology</h2>
        <!-- Title for this category -->

        <div class="grid">
            <!-- Container for technology cards -->

            <?php for ($i = 1; $i <= 5; $i++): ?>
            <!-- PHP loop to generate 5 technology talent cards -->

            <div class="talent-card category-technology">
                <!-- Single card styled as technology category -->

                <img src="images/video<?= $i ?>.jpg" alt="Video <?= $i ?>">
                <!-- Image path uses PHP to dynamically insert the number (e.g., video1.jpg) -->

                <p>Video <?= $i ?><br><small>Description</small></p>
                <!-- Text showing video number and description -->
            </div>

            <?php endfor; ?>
            <!-- End of PHP loop -->
        </div>
    </section>

    <section class="talent-section" id="art">
        <!-- Section for Art talents -->

        <h2>Art</h2>
        <!-- Title for this category -->

        <div class="grid">
            <!-- Container for art cards -->

            <?php for ($i = 1; $i <= 5; $i++): ?>
            <!-- PHP loop to generate 5 art talent cards -->

            <div class="talent-card category-art">
                <!-- Single card styled as art category -->

                <img src="images/art<?= $i ?>.jpg" alt="Artwork <?= $i ?>">
                <!-- Image path uses PHP to dynamically insert the number (e.g., art1.jpg) -->

                <p>Artwork <?= $i ?><br><small>Description</small></p>
                <!-- Text showing artwork number and description -->
            </div>

            <?php endfor; ?>
            <!-- End of PHP loop -->
        </div>
    </section>

</body>
</html>
