function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    let count = 0;
    cart.forEach(item => count += item.qty);
    const counter = document.getElementById("cartCount");
    if (counter) counter.innerText = count;
}

function renderCart() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const container = document.getElementById("cart-items");
    container.innerHTML = "";

    let subtotal = 0;

    cart.forEach((item, index) => {
        const total = item.qty * item.price;
        subtotal += total;

        const div = document.createElement("div");
        div.className = "cart-item";
        div.innerHTML = `
            <div class="cart-item-name">${item.name}</div>
            <div class="cart-item-price">$${item.price.toFixed(2)}</div>
            <div class="cart-item-qty">${item.qty}</div>
            <div class="cart-item-total">$${total.toFixed(2)}</div>
            <button onclick="removeItem(${index})">Remove</button>
        `;
        container.appendChild(div);
    });

    document.getElementById("subtotal").innerText = "Subtotal: $" + subtotal.toFixed(2);
    updateCartCount();
}

function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
}

window.onload = function () {
    renderCart();
};