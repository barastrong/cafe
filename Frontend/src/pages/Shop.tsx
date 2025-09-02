import React from 'react';
import '../css/Shop.css';

const products = [
  { id: 1, name: 'Smart Watch Gen 5', price: 'Rp 2.500.000', image: 'https://via.placeholder.com/300' },
  { id: 2, name: 'Wireless Earbuds Pro', price: 'Rp 1.200.000', image: 'https://via.placeholder.com/300' },
  { id: 3, name: 'Mechanical Keyboard', price: 'Rp 950.000', image: 'https://via.placeholder.com/300' },
  { id: 4, name: '4K Webcam', price: 'Rp 750.000', image: 'https://via.placeholder.com/300' },
];

const Shop: React.FC = () => {
  return (
    <div className="shop-page">
      <h1>Katalog Produk Kami</h1>
      <div className="product-grid">
        {products.map((product) => (
          <div key={product.id} className="product-card">
            <img src={product.image} alt={product.name} className="product-image" />
            <div className="product-info">
              <h3 className="product-name">{product.name}</h3>
              <p className="product-price">{product.price}</p>
              <button className="btn-buy">Beli Sekarang</button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Shop;