window.onload = function() {
    const boxes = document.querySelectorAll('.talent-box');
    let position = 0;
    const visibleBoxes = 3;
    const totalPages = Math.ceil(boxes.length / visibleBoxes);

    function updateCarousel() {
        boxes.forEach((box, index) => {
            if (index >= position && index < position + visibleBoxes) {
                box.style.display = 'flex';
            } else {
                box.style.display = 'none';
            }
        });
    }

    function scrollLeft() {
        if ((position / visibleBoxes) + 1 < totalPages) {
            position += visibleBoxes;
            updateCarousel();
        }
    }

    function scrollRight() {
        if (position > 0) {
            position -= visibleBoxes;
            updateCarousel();
        }
    }

    updateCarousel();

    document.querySelector('.arrow.left').onclick = scrollLeft;
    document.querySelector('.arrow.right').onclick = scrollRight;
}
