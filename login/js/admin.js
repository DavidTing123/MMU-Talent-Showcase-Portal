const buttons = document.querySelectorAll('.tab-btn');
const contents = document.querySelectorAll('.tab-content');

buttons.forEach(btn => {
    btn.addEventListener('click', () => {
        // Remove active class from all buttons
        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Hide all tab contents
        contents.forEach(c => c.classList.add('hidden'));

        // Show selected tab
        const tabId = btn.getAttribute('data-tab');
        document.getElementById(tabId).classList.remove('hidden');
    });
});
