document.addEventListener('DOMContentLoaded', () => {
    fetch('php/fetch_products.php')
        .then(response => response.json())
        .then(products => {
            let productList = document.getElementById('product-list');
            products.forEach(product => {
                productList.innerHTML += `
                    <div class="product">
                        <img src="images/${product.image}" alt="${product.name}">
                        <h3>${product.name}</h3>
                        <p>${product.description}</p>
                        <p>₹${product.price}</p>
                    </div>
                `;
            });
        })
        .catch(error => console.log('Error fetching products:', error));
});
