import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiRoutes from '../routes/apiRoutes';
import '../css/Home.css';

// --- Type Definitions ---
interface Menu {
  id: number;
  name: string;
  slug: string;
}

interface Product {
  id: number;
  name: string;
  slug: string;
  image: string;
  price: number;
  stock: number;
  menu: Menu;
}

// --- Constants ---
const API_BASE_URL = 'http://127.0.0.1:8000';

// --- Child Components (Co-located for simplicity) ---

const HeroSection: React.FC = () => (
  <div className="hero-container">
    <h1>Selamat Datang di MyStore</h1>
    <p>Temukan produk terbaik dengan penawaran luar biasa.</p>
    <div className="hero-btns">
      <Link to="/shop" className="btn btn--primary btn--large">
        Belanja Sekarang
      </Link>
    </div>
  </div>
);

interface ProductCardProps {
  product: Product;
}

const ProductCard: React.FC<ProductCardProps> = ({ product }) => (
  <div className="product-card">
    <div className="product-image-wrapper">
      <img
        src={`${API_BASE_URL}/storage/${product.image}`}
        alt={product.name}
        className="product-image"
      />
    </div>
    <div className="product-info">
      <span className="product-menu">{product.menu.name}</span>
      <h3 className="product-name">{product.name}</h3>
      <p className="product-price">
        Rp {product.price.toLocaleString('id-ID')}
      </p>
      <Link to={`/product/${product.slug}`} className="btn btn--detail">
        Lihat Detail
      </Link>
    </div>
  </div>
);

// --- Main Page Component ---

const Home: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchProducts = async () => {
      setIsLoading(true);
      setError(null);
      try {
        const response = await apiRoutes.get('/products');
        if (response.data.success) {
          setProducts(response.data.data);
        } else {
          throw new Error(response.data.message || 'Gagal mengambil data produk.');
        }
      } catch (err: unknown) {
        let errorMessage = 'Terjadi kesalahan saat memuat data. Coba lagi nanti.';
        if (err instanceof Error) {
          errorMessage = err.message;
        }
        setError(errorMessage);
        console.error("Fetch products error:", err);
      } finally {
        setIsLoading(false);
      }
    };

    fetchProducts();
  }, []);

  const renderContent = () => {
    if (isLoading) {
      return <p className="status-message">Memuat produk...</p>;
    }

    if (error) {
      return <p className="status-message error">{error}</p>;
    }

    if (products.length === 0) {
      return <p className="status-message">Belum ada produk yang tersedia.</p>;
    }

    return (
      <div className="products-container">
        {products.map((product) => (
          <ProductCard key={product.id} product={product} />
        ))}
      </div>
    );
  };

  return (
    <>
      <HeroSection />
      <section className="products-section">
        <div className="container">
          <h2>Produk Terbaru Kami</h2>
          {renderContent()}
        </div>
      </section>
    </>
  );
};

export default Home;