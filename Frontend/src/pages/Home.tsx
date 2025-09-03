import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import apiRoutes from '../routes/apiRoutes';
import ProductCard from '../components/ProductCardPopuler';
import NetworkError from '../components/NetworkError';
import HeaderImage from '../assets/header.png';

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

const HeroSection: React.FC = () => {
  return (
    <div className="w-full bg-[#4a372d] py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto bg-[#EAE1D4] rounded-3xl shadow-xl relative">
        <div className="grid grid-cols-1 md:grid-cols-2">
          <div className="p-8 md:p-12 lg:p-16 relative z-10 text-center md:text-left">
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight">
              <span className="whitespace-nowrap">Homemade Coffe</span>
              <span className="block mt-2">Amazing Vibes</span>
            </h1>
            <p className="mt-6 text-lg text-gray-600 leading-relaxed max-w-md mx-auto md:mx-0">
              Tempat terbaik buat santai, kerja, atau nongkrong bareng teman. Nikmati racikan kopi terbaik dan camilan yang bikin betah berlama-lama di café kami.
            </p>
            <div className="mt-8">
              <Link
                to="/shop"
                className="inline-block bg-slate-800 text-white font-semibold px-8 py-3 rounded-lg hover:bg-slate-700 transition-colors duration-300 shadow-lg"
              >
                Jelajahi
              </Link>
            </div>
          </div>
        </div>
        <div className="hidden md:block absolute bottom-0 right-0 w-1/2 lg:w-[55%] pointer-events-none">
          <img 
            src={HeaderImage} 
            alt="Splashing coffee cup" 
            className="absolute bottom-0 -right-16 lg:-right-20 w-[550px] lg:w-[700px] max-w-none"
          />
        </div>
      </div>
    </div>
  );
};

const Home: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

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
      let errorMessage = 'Terjadi kesalahan pada server.';
      if (axios.isAxiosError(err) && err.message === 'Network Error') {
        errorMessage = 'Network Error';
      } else if (err instanceof Error) {
        errorMessage = err.message;
      }
      setError(errorMessage);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchProducts();
  }, []);

  const renderContent = () => {
    if (isLoading) {
      return <p className="text-center py-20 text-slate-400">Memuat produk...</p>;
    }

    if (error === 'Network Error') {
      return <NetworkError onRetry={fetchProducts} />;
    }

    if (error) {
      return <p className="text-center py-20 text-red-400">{error}</p>;
    }

    if (products.length === 0) {
      return <p className="text-center py-20 text-slate-400">Belum ada produk yang tersedia.</p>;
    }

    return (
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-28">
        {products.map((product) => (
          <ProductCard key={product.id} product={product} />
        ))}
      </div>
    );
  };

  return (
    <div className=" w-full min-h-screen overflow-x-hidden pt-45">
      <HeroSection />

      <section className="py-16">
        <div className="container mx-auto px-4">
          <div className="text-center mb-20">
            <h2 className="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white">
              3 Top <span className="text-yellow-300">Coffe</span> Categories
            </h2>
            <p className="mt-4 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto">
              Bold and rich, your instant boost in every sip
            </p>
          </div>
          {renderContent()}
        </div>
      </section>

      <section className="w-full bg-[#EAE1D4] py-16">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-800">
            Top <span className="text-orange-500">Snack</span> Categories
          </h2>
          <p className="mt-4 text-base sm:text-lg text-gray-600 max-w-3xl mx-auto">
            From sweet to savory, the perfect partner for your coffee moments
          </p>
        </div>
      </section>

    </div>
  );
};

export default Home;