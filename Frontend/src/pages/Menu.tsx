import React, { useState, useMemo } from 'react';
import { FaSearch, FaShoppingCart, FaPlus } from 'react-icons/fa';

const coffeeImage1 = '/path/to/your/assets/menu/coffee-1.png';
const coffeeImage2 = '/path/to/your/assets/menu/coffee-2.png';
const foodImage1 = '/path/to/your/assets/menu/food-1.png';
const snackImage1 = '/path/to/your/assets/menu/snack-1.png';
const dessertImage1 = '/path/to/your/assets/menu/dessert-1.png';

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
  <div className="bg-[#EDE4D5] rounded-2xl p-4 flex gap-4 group border border-orange-600 hover:shadow-xl transition-all">
    <img src={product.imageUrl} alt={product.name} className="w-24 h-24 object-cover rounded-lg flex-shrink-0" />
    <div className="flex flex-col flex-grow">
      <h3 className="text-slate-800 font-bold text-lg">{product.name}</h3>
      <div className="flex justify-between items-center mt-auto">
        <p className="bg-orange-500 text-white font-semibold px-4 py-1 rounded-full border text-sm">
        Rp {product.price.toLocaleString('id-ID')}
        </p>
        <button className="bg-orange-500 text-white rounded-full w-9 h-9 flex items-center justify-center transition-all hover:bg-orange-600 hover:scale-110">
          <FaPlus size={14} />
        </button>
      </div>
    </div>
  </div>
);

const MenuPage: React.FC = () => {
  const [searchQuery, setSearchQuery] = useState('');
  const [activeCategory, setActiveCategory] = useState('All');
  const [activeSubCategory, setActiveSubCategory] = useState('All');

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
    <main className="bg-[#4a372d] pt-24 pb-12 min-h-screen">
      <div className="max-w-screen-xl mx-auto px-6 lg:px-8">
        
        <header className="text-center py-12">
            <h1 className="text-5xl md:text-7xl font-extrabold text-white">Our Signature <span className="text-orange-500">Menu</span></h1>
            <p className="mt-4 text-lg text-gray-300 max-w-2xl mx-auto">
                Setiap menu dibuat dengan bahan-bahan terbaik dan sentuhan gairah dari para barista dan chef kami.
            </p>
        </header>

        <div className=" top-24 z-20 bg-[#4a372d] py-4">
            <div className="relative mb-6">
                <FaSearch className="absolute top-1/2 left-5 -translate-y-1/2 text-gray-400" />
                <input
                type="text"
                placeholder="Cari Kopi, Snack, atau Apapun..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full bg-[#EDE4D5] border border-gray-300 text-slate-800 placeholder-gray-500 rounded-full py-3 pl-12 pr-6 focus:outline-none focus:ring-2 focus:ring-orange-500"
                />
            </div>
            <div className="flex justify-center flex-wrap gap-3">
                {categories.map(cat => (
                    <button 
                    key={cat}
                    onClick={() => handleCategoryClick(cat)}
                    className={`px-6 py-2 rounded-full font-semibold text-lg transition-all duration-300 ${
                        activeCategory === cat 
                        ? 'bg-orange-500 text-white shadow-lg' 
                        : 'bg-[#EDE4D5] text-gray-700'
                    }`}
                    >
                    {cat}
                    </button>
                ))}
            </div>
            {activeCategory !== 'All' && (
                <div className="flex justify-center flex-wrap gap-2 mt-6">
                    {subCategoryData[activeCategory].map(subCat => (
                        <button
                            key={subCat}
                            onClick={() => setActiveSubCategory(subCat)}
                            className={`px-4 py-1.5 rounded-full text-sm font-semibold transition-colors duration-200 border-2 ${
                                activeSubCategory === subCat
                                ? 'bg-orange-400 border-orange-400 text-slate-900'
                                : 'bg-transparent border-slate-600 text-gray-300'
                            }`}
                        >
                            {subCat}
                        </button>
                    ))}
                </div>
            )}
        </div>

        <section className="mt-8">
            {totalFilteredProducts > 0 ? (
                <div className="space-y-12">
                    {categoriesToRender.map(category => (
                        <div key={category.name}>
                        <h2 className="text-3xl font-bold text-white mb-6 border-b-2 border-slate-600 pb-2">{category.name}</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            {category.products.map(product => (
                                <ProductCard key={product.id} product={product} />
                            ))}
                        </div>
                        </div>
                    ))}
                </div>
            ) : (
                <div className="md:col-span-2 xl:grid-cols-3 text-center py-16 text-gray-400">
                    <h3 className="text-2xl font-bold text-white">Oops!</h3>
                    <p>Tidak ada produk yang cocok dengan pencarian Anda.</p>
                </div>
            )}
        </section>
      </div>
      
      <div className="fixed bottom-8 right-8 z-30">
        <button className="bg-white text-orange-500 w-16 h-16 rounded-full flex items-center justify-center shadow-lg transform transition-transform hover:scale-110">
            <FaShoppingCart size={24} />
        </button>
      </div>

    </main>
  );
};

export default MenuPage;