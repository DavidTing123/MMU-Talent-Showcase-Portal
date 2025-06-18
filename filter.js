// Function to filter talents by category (Music, Technology, Art, or All)
function filterCategory(category) {
    // Select all elements with the class 'talent-card'
    let allCards = document.querySelectorAll('.talent-card');

    // Loop through each card
    allCards.forEach(card => {
        // If 'all' is selected, show all cards
        if (category === 'all') {
            card.style.display = 'block'; // make card visible
        } else {
            // Otherwise, check if the card has a matching category class
            // Show it only if it matches the selected category
            card.style.display = card.classList.contains('category-' + category) ? 'block' : 'none';
        }
    });
}

// Function to search talents by keyword
function searchTalent() {
    // Get the value entered in the search box and convert to lowercase
    const keyword = document.getElementById('search').value.toLowerCase();

    // Select all elements with the class 'talent-card'
    const allCards = document.querySelectorAll('.talent-card');

    // Loop through each card
    allCards.forEach(card => {
        // Get the text content of the card and convert to lowercase
        const text = card.innerText.toLowerCase();

        // Check if the card's text includes the keyword
        // If yes, show the card, otherwise hide it
        card.style.display = text.includes(keyword) ? 'block' : 'none';
    });
}
