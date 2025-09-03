import React from 'react';
import { FaPlus } from 'react-icons/fa';

interface Product {
  id: number;
  name: string;
  slug: string;
  image: string;
  price: number;
}

interface ProductCardProps {
  product: Product;
}

const API_BASE_URL = 'http://127.0.0.1:8000';

const ProductCardPopuler: React.FC<ProductCardProps> = ({ product }) => {
  return (
    <div className="relative pt-20">
      <div className="absolute top-0 left-1/2 -translate-x-1/2 z-10">
        <img
          src={`${API_BASE_URL}/storage/${product.image}`}
          alt={product.name}
          className="w-48 h-48 object-contain drop-shadow-2xl transition-transform duration-300 hover:scale-110"
        />
      </div>

      <div className="relative bg-[#EAE1D4] rounded-3xl shadow-lg pt-32 text-center">
        <div className="bg-white rounded-b-3xl border-t border-gray-200 px-5 py-4">
          <h3 className="text-xl font-bold text-slate-800 truncate mb-4">
            {product.name}
          </h3>

          <div className="flex justify-between items-center">
            <p className="bg-[#4a372d] text-[#D5B797] text-lg font-semibold px-5 py-2 rounded-full">
              Rp. {product.price.toLocaleString('id-ID')}
            </p>

            <button className="bg-yellow-500 hover:bg-yellow-600 text-black rounded-full w-12 h-12 flex items-center justify-center transition-transform duration-200 hover:scale-110 shadow-md">
              <FaPlus size={20} />
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductCardPopuler;