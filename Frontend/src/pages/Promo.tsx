import React, { useState } from 'react';
import { FaPercentage, FaTags } from 'react-icons/fa';

import coffeeBeansImage from '../assets/CoffeBean.png';

const promoCategories = ['All', 'Coffe', 'Makanan', 'Snack', 'Dessert'];
const promoPosters = [
  { id: 1, category: 'Coffe' },
  { id: 2, category: 'Makanan' },
  { id: 3, category: 'Coffe' },
  { id: 4, category: 'Snack' },
];

const PromoPage: React.FC = () => {
  const [activeCategory, setActiveCategory] = useState('All');

  const filteredPromos = activeCategory === 'All'
    ? promoPosters
    : promoPosters.filter(p => p.category === activeCategory);

  return (
    <main className="pt-32">
      <div className="container mx-auto px-6 lg:px-8">
        
        <section className="text-left mb-16">
          <h1 className="text-5xl md:text-6xl font-extrabold tracking-tight text-white">
            <span className="text-orange-400">Promos</span> for a great time
          </h1>
          <p className="mt-4 text-lg text-gray-300 max-w-2xl">
            "Nikmati momen seru dengan penawaran spesial dan promo menarik dari kami. Lebih hemat, lebih menyenangkan!"
          </p>
        </section>

        <section className="text-center">
          <h2 className="text-4xl md:text-5xl font-bold text-white">
            Enjoy Hot <span className="text-orange-400">Promos!</span>
          </h2>
          <div className="flex justify-center flex-wrap gap-3 md:gap-4 mt-8">
            {promoCategories.map(category => (
              <button
                key={category}
                onClick={() => setActiveCategory(category)}
                className={`px-6 py-2 rounded-lg font-semibold transition-colors duration-300 border-2 ${
                  activeCategory === category
                    ? 'bg-orange-400 border-orange-400 text-slate-900'
                    : 'bg-transparent border-gray-500 text-gray-300 hover:bg-gray-700 hover:border-gray-700'
                }`}
              >
                {category}
              </button>
            ))}
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
            {filteredPromos.map(promo => (
              <div key={promo.id} className="rounded-2xl bg-slate-700 aspect-[3/4] flex flex-col items-center justify-center p-4 text-center text-white transform transition-transform hover:scale-105">
                <FaTags className="text-5xl text-orange-400" />
                <h3 className="mt-4 font-bold text-lg">Promo Poster</h3>
                <p className="text-sm text-slate-400">{promo.category}</p>
              </div>
            ))}
          </div>
        </section>

        <section className="mt-24">
          <div className="bg-[#EAE1D4] rounded-3xl p-8 md:p-12 grid md:grid-cols-2 gap-8 items-center relative overflow-hidden">
            <img 
              src={coffeeBeansImage}
              alt="Coffee beans decoration"
              className="absolute -top-10 -right-20 w-64 h-auto opacity-30 md:opacity-100"
            />
            <div className="relative z-10 flex flex-col items-center md:items-start space-y-4">
              <h2 className="text-5xl font-extrabold text-slate-800">Join</h2>
              <h3 className="text-5xl font-extrabold text-slate-800">
                <span className="text-orange-500">KatanyaCafe</span> VIP
              </h3>
              <button className="bg-orange-500 text-slate-900 font-bold px-8 py-3 rounded-xl hover:bg-orange-600 transition-colors shadow-md text-lg">
                Bergabung
              </button>
            </div>
            <div className="relative z-10">
              <h3 className="text-2xl font-bold text-slate-800">Tentang Loyalty <span className="text-orange-500">KatanyaCafe</span> VIP</h3>
              <p className="mt-4 text-gray-600">
                KatanyaCafe VIP merupakan program loyalitas dari Kenangan Brands yang dihadirkan khusus untuk teman pecinta nongki dimana nantinya akan ada banyak benefit spesial untuk para teman.
              </p>
            </div>
          </div>
        </section>

        <section className="py-24">
          <div className="text-left mb-12">
            <h2 className="text-4xl md:text-5xl font-bold text-white">Apa yang akan Kamu Dapatkan?</h2>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div className="border-2 border-orange-400 rounded-2xl p-6 min-h-[200px] flex flex-col justify-center items-start text-left">
              <FaPercentage className="text-5xl text-orange-400 mb-4" />
              <h3 className="text-xl font-bold text-white">Regular Cashback</h3>
              <p className="text-gray-400 mt-2 text-sm">Cashback KatanyaCafe Points untuk setiap transaksi</p>
            </div>
            <div className="border-2 border-dashed border-gray-600 rounded-2xl min-h-[200px]"></div>
            <div className="border-2 border-dashed border-gray-600 rounded-2xl min-h-[200px]"></div>
            <div className="border-2 border-dashed border-gray-600 rounded-2xl min-h-[200px]"></div>
          </div>
        </section>

      </div>
    </main>
  );
};

export default PromoPage;