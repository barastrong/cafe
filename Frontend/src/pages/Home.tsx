import React, { useState, useMemo } from 'react';
import { Search, ShoppingCart, Plus } from 'lucide-react';

const coffeeImage1 = 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400';
const coffeeImage2 = 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400';
const foodImage1 = 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400';
const snackImage1 = 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400';
const dessertImage1 = 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400';

interface Product {
  id: number;
  name: string;
  price: number;
  imageUrl: string;
  category: string;
  subCategory: string;
}

const menuData: Product[] = [
  { id: 1, name: 'Ice Caramel Macchiato', price: 25000, imageUrl: coffeeImage1, category: 'Coffe', subCategory: 'Coffe Series' },
  { id: 2, name: 'Vanilla Latte Frappe', price: 28000, imageUrl: coffeeImage2, category: 'Coffe', subCategory: 'Frappe Series' },
  { id: 3, name: 'Hazelnut Milk Coffee', price: 24000, imageUrl: coffeeImage1, category: 'Coffe', subCategory: 'Milk Series' },
  { id: 4, name: 'Nasi Goreng Spesial', price: 35000, imageUrl: foodImage1, category: 'Makanan', subCategory: 'Main Course' },
  { id: 5, name: 'Kentang Goreng Truffle', price: 22000, imageUrl: snackImage1, category: 'Snack', subCategory: 'Fried' },
  { id: 6, name: 'Chocolate Lava Cake', price: 30000, imageUrl: dessertImage1, category: 'Dessert', subCategory: 'Cakes' },
  { id: 7, name: 'Classic Black Coffee', price: 18000, imageUrl: coffeeImage2, category: 'Coffe', subCategory: 'Coffe Series' },
];

const categories = ['All', 'Coffe', 'Makanan', 'Snack', 'Dessert'];

const subCategoryData: { [key: string]: string[] } = {
  Coffe: ['All', 'Coffe Series', 'Frappe Series', 'Milk Series'],
  Makanan: ['All', 'Main Course', 'Appetizer'],
  Snack: ['All', 'Fried', 'Pastry'],
  Dessert: ['All', 'Cakes', 'Ice Cream'],
};

const ProductCard: React.FC<{ product: Product }> = ({ product }) => (
  <div className="bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl p-4 sm:p-5 flex flex-col sm:flex-row gap-4 group border-2 border-orange-200 hover:border-orange-400 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
    <div className="w-full sm:w-32 h-32 sm:h-32 rounded-2xl overflow-hidden flex-shrink-0 shadow-lg">
      <img 
        src={product.imageUrl} 
        alt={product.name} 
        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
      />
    </div>
    <div className="flex flex-col flex-grow justify-between">
      <h3 className="text-slate-800 font-bold text-lg sm:text-xl mb-2 group-hover:text-orange-600 transition-colors">
        {product.name}
      </h3>
      <div className="flex justify-between items-center gap-3 mt-auto">
        <p className="bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold px-4 sm:px-5 py-2 rounded-full shadow-md text-sm sm:text-base">
          Rp {product.price.toLocaleString('id-ID')}
        </p>
        <button className="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-full w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center transition-all hover:shadow-lg hover:scale-110 active:scale-95">
          <Plus size={18} strokeWidth={3} />
        </button>
      </div>
    </div>
  </div>
);

const MenuPage: React.FC = () => {
  const [searchQuery, setSearchQuery] = useState('');
  const [activeCategory, setActiveCategory] = useState('All');
  const [activeSubCategory, setActiveSubCategory] = useState('All');
  const [cartCount] = useState(0);

  const handleCategoryClick = (category: string) => {
    setActiveCategory(category);
    setActiveSubCategory('All');
  };

  const categoriesToRender = useMemo(() =>
    (activeCategory === 'All' ? categories.slice(1) : [activeCategory])
      .map(catName => ({
        name: catName,
        products: menuData
          .filter(p => p.category === catName)
          .filter(p => activeCategory === 'All' || activeSubCategory === 'All' || p.subCategory === activeSubCategory)
          .filter(p => p.name.toLowerCase().includes(searchQuery.toLowerCase())),
      }))
      .filter(cat => cat.products.length > 0),
    [activeCategory, activeSubCategory, searchQuery]
  );
  
  const totalFilteredProducts = categoriesToRender.reduce((sum, cat) => sum + cat.products.length, 0);

  return (
    <div className="min-h-screen">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        
        <header className="text-center py-6 sm:py-10 mb-4 sm:mb-6">
          <h1 className="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-white mb-3 sm:mb-4 tracking-tight">
            Our Signature <span className="text-transparent bg-clip-text bg-orange-400">Menu</span>
          </h1>
          <p className="mt-3 sm:mt-4 text-base sm:text-lg text-amber-200 max-w-2xl mx-auto px-4">
            Setiap menu dibuat dengan bahan-bahan terbaik dan sentuhan gairah dari para barista dan chef kami.
          </p>
        </header>

        <div className="sticky top-16 sm:top-20 z-30 to-transparent py-4 sm:py-6 pb-6 sm:pb-8 backdrop-blur-sm">
          <div className="relative mb-4 sm:mb-6">
            <Search className="absolute top-1/2 left-4 sm:left-5 -translate-y-1/2 text-gray-400" size={20} />
            <input
              type="text"
              placeholder="Cari Kopi, Snack, atau Apapun..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="w-full bg-white/95 backdrop-blur-sm border-2 border-orange-200 text-slate-800 placeholder-gray-500 rounded-full py-3 sm:py-3.5 pl-11 sm:pl-12 pr-6 focus:outline-none focus:ring-4 focus:ring-orange-300 focus:border-orange-400 shadow-lg text-sm sm:text-base transition-all"
            />
          </div>
          
          <div className="flex justify-start sm:justify-center overflow-x-auto gap-2 sm:gap-3 pb-2 scrollbar-hide">
            {categories.map(cat => (
              <button 
                key={cat}
                onClick={() => handleCategoryClick(cat)}
                className={`px-4 sm:px-6 py-2 sm:py-2.5 rounded-full font-bold text-sm sm:text-base whitespace-nowrap transition-all duration-300 flex-shrink-0 ${
                  activeCategory === cat 
                    ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-xl scale-105' 
                    : 'bg-white/90 text-gray-700 hover:bg-white hover:shadow-lg'
                }`}
              >
                {cat}
              </button>
            ))}
          </div>
          
          {activeCategory !== 'All' && (
            <div className="flex justify-start sm:justify-center overflow-x-auto gap-2 mt-4 sm:mt-6 pb-2 scrollbar-hide">
              {subCategoryData[activeCategory].map(subCat => (
                <button
                  key={subCat}
                  onClick={() => setActiveSubCategory(subCat)}
                  className={`px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-bold transition-all duration-200 border-2 whitespace-nowrap flex-shrink-0 ${
                    activeSubCategory === subCat
                      ? 'bg-orange-400 border-orange-400 text-white shadow-lg scale-105'
                      : 'bg-transparent border-orange-300 text-orange-200 hover:bg-orange-400/20'
                  }`}
                >
                  {subCat}
                </button>
              ))}
            </div>
          )}
        </div>

        <section className="mt-4 sm:mt-8 pb-8">
          {totalFilteredProducts > 0 ? (
            <div className="space-y-8 sm:space-y-12">
              {categoriesToRender.map(category => (
                <div key={category.name}>
                  <h2 className="text-2xl sm:text-3xl font-bold text-white mb-4 sm:mb-6 pb-2 border-b-2 border-orange-400/50 flex items-center gap-3">
                    <span className="w-2 h-8 bg-gradient-to-b from-orange-400 to-orange-600 rounded-full"></span>
                    {category.name}
                  </h2>
                  <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                    {category.products.map(product => (
                      <ProductCard key={product.id} product={product} />
                    ))}
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="text-center py-16 sm:py-24 px-4">
              <div className="bg-white/10 backdrop-blur-sm rounded-3xl p-8 sm:p-12 max-w-md mx-auto border border-orange-400/30">
                <div className="text-6xl mb-4">😔</div>
                <h3 className="text-2xl sm:text-3xl font-bold text-white mb-3">Oops!</h3>
                <p className="text-amber-200 text-sm sm:text-base">Tidak ada produk yang cocok dengan pencarian Anda.</p>
              </div>
            </div>
          )}
        </section>
      </div>
      
      <div className="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 z-40">
        <button className="bg-white text-orange-600 w-14 h-14 sm:w-16 sm:h-16 rounded-full flex items-center justify-center shadow-2xl transform transition-all hover:scale-110 active:scale-95 relative group">
          <ShoppingCart size={24} className="sm:w-7 sm:h-7" strokeWidth={2.5} />
          {cartCount > 0 && (
            <span className="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
              {cartCount}
            </span>
          )}
          <div className="absolute inset-0 rounded-full bg-orange-400 opacity-0 group-hover:opacity-20 transition-opacity"></div>
        </button>
      </div>

      <style>{`
        .scrollbar-hide::-webkit-scrollbar {
          display: none;
        }
        .scrollbar-hide {
          -ms-overflow-style: none;
          scrollbar-width: none;
        }
      `}</style>
    </div>
  );
};

export default MenuPage;  