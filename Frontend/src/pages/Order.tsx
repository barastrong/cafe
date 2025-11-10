import React, { useState, useMemo, useEffect } from 'react';
import { FaPlus, FaMinus, FaTrash, FaUser, FaStickyNote, FaArrowLeft, FaShoppingCart } from 'react-icons/fa';
import { motion, AnimatePresence } from 'framer-motion';

const coffeeImage1 = 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400';
const snackImage1 = 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400';

interface Product {
  id: number;
  name: string;
  price: number;
  imageUrl: string;
}

interface OrderItem {
  product: Product;
  quantity: number;
}

const initialCartData: Product[] = [
  { id: 1, name: 'Ice Caramel Macchiato', price: 25000, imageUrl: coffeeImage1 },
  { id: 5, name: 'Kentang Goreng Truffle', price: 22000, imageUrl: snackImage1 },
  { id: 1, name: 'Ice Caramel Macchiato', price: 25000, imageUrl: coffeeImage1 },
];

const OrderItemCard: React.FC<{ item: OrderItem; onQuantityChange: (id: number, amount: number) => void; onRemove: (id: number) => void; }> = ({ item, onQuantityChange, onRemove }) => {
  const { product, quantity } = item;
  return (
    <motion.div
      layout
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      exit={{ opacity: 0, x: -50, transition: { duration: 0.2 } }}
      className="flex items-center gap-4 py-5 border-b border-amber-200/40 last:border-0"
    >
      <div className="relative">
        <img 
          src={product.imageUrl} 
          alt={product.name} 
          className="w-20 h-20 object-cover rounded-xl flex-shrink-0 shadow-md border-2 border-amber-100" 
        />
      </div>
      <div className="flex-grow">
        <h3 className="text-amber-950 font-bold text-lg mb-1">{product.name}</h3>
        <p className="text-orange-600 font-semibold">Rp {product.price.toLocaleString('id-ID')}</p>
      </div>
      <div className="flex items-center gap-3 bg-amber-50 rounded-full px-2 py-1 border border-orange-300">
        <button 
          onClick={() => onQuantityChange(product.id, -1)} 
          className="bg-white text-orange-600 w-8 h-8 rounded-full flex items-center justify-center hover:bg-orange-50 transition-all shadow-sm border border-orange-200 hover:border-orange-400"
        >
          <FaMinus size={12} />
        </button>
        <span className="text-amber-950 font-bold text-lg w-8 text-center">{quantity}</span>
        <button 
          onClick={() => onQuantityChange(product.id, 1)} 
          className="bg-orange-500 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-orange-600 transition-all shadow-sm"
        >
          <FaPlus size={12} />
        </button>
      </div>
      <button 
        onClick={() => onRemove(product.id)} 
        className="text-red-500 transition-colors pl-2 hover:scale-110 transition-transform"
      >
        <FaTrash size={18} />
      </button>
    </motion.div>
  );
};

const OrderPage: React.FC = () => {
  const [name, setName] = useState('');
  const [notes, setNotes] = useState('');
  const [orderItems, setOrderItems] = useState<OrderItem[]>([]);

  useEffect(() => {
    const groupedItems: { [key: number]: OrderItem } = {};
    initialCartData.forEach(product => {
      if (groupedItems[product.id]) {
        groupedItems[product.id].quantity++;
      } else {
        groupedItems[product.id] = { product, quantity: 1 };
      }
    });
    setOrderItems(Object.values(groupedItems));
  }, []);

  const handleQuantityChange = (productId: number, amount: number) => {
    setOrderItems(
      orderItems
        .map(item =>
          item.product.id === productId
            ? { ...item, quantity: item.quantity + amount }
            : item
        )
        .filter(item => item.quantity > 0)
    );
  };

  const handleRemoveItem = (productId: number) => {
    setOrderItems(orderItems.filter(item => item.product.id !== productId));
  };

  const subtotal = useMemo(() =>
    orderItems.reduce((total, item) => total + item.product.price * item.quantity, 0),
    [orderItems]
  );

  const tax = subtotal * 0.11;
  const total = subtotal + tax;

  return (
    <main className="pt-24 pb-32 lg:pb-12 min-h-screen">
      <div className="max-w-screen-xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-start">
        
        <div className="lg:col-span-2">
          <div className="flex items-center gap-4 mb-6">
            <a href="/menu" className="bg-[#EAE1D4] text-orange-600 rounded-full p-3 hover:bg-orange-200 transition-all shadow-md hover:shadow-lg border-2 border-orange-200">
              <FaArrowLeft size={18} />
            </a>
            <h1 className="text-3xl sm:text-4xl font-extrabold text-white">Keranjang Belanja</h1>
          </div>
          
          <div className="bg-[#EAE1D4] border-2 border-orange-400 rounded-2xl p-5 sm:p-7 shadow-xl">
            <AnimatePresence>
              {orderItems.length > 0 ? (
                orderItems.map(item => (
                  <OrderItemCard 
                    key={item.product.id}
                    item={item}
                    onQuantityChange={handleQuantityChange}
                    onRemove={handleRemoveItem}
                  />
                ))
              ) : (
                <motion.div
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 1 }}
                  className="text-center py-16"
                >
                  <FaShoppingCart className="text-amber-300 text-6xl mx-auto mb-4" />
                  <p className="text-amber-700 text-lg font-medium">Keranjang Anda kosong.</p>
                </motion.div>
              )}
            </AnimatePresence>
          </div>
        </div>

        <aside className="lg:sticky lg:top-32 h-fit">
          <div className="bg-[#EAE1D4] border-2 border-amber-200 rounded-2xl p-6 shadow-xl">
            <div className="flex items-center gap-3 border-b-2 border-amber-300 pb-4 mb-5">
              <FaShoppingCart className="text-orange-600 text-2xl" />
              <h2 className="text-2xl font-bold">Ringkasan Pesanan</h2>
            </div>

            <div className="mb-5 space-y-4">
              <div className="relative">
                <FaUser className="absolute top-1/2 left-4 -translate-y-1/2 text-orange-500" />
                <input
                  type="text"
                  placeholder="Nama Anda *"
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                  className="w-full bg-white border-2 border-orange-200  placeholder-gray-400 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:ring-1 focus:ring-orange-400 focus:border-orange-400 shadow-sm font-medium"
                />
              </div>
              <div className="relative">
                <FaStickyNote className="absolute top-4 left-4 text-orange-500" />
                <textarea
                  placeholder="Tambah Catatan (opsional)"
                  value={notes}
                  onChange={(e) => setNotes(e.target.value)}
                  rows={3}
                  className="w-full bg-white border-2 border-orange-200 placeholder-gray-400 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:ring-1 focus:ring-orange-400 focus:border-orange-400 shadow-sm font-medium resize-none"
                />
              </div>
            </div>
            
            <div className="space-y-3 text-amber-900 mb-5">
              <div className="flex justify-between items-center bg-white rounded-lg px-4 py-3 shadow-sm">
                <span className="font-medium">Subtotal</span>
                <span className="font-semibold">Rp {subtotal.toLocaleString('id-ID')}</span>
              </div>
              <div className="flex justify-between items-center bg-white rounded-lg px-4 py-3 shadow-sm">
                <span className="font-medium">PPN (11%)</span>
                <span className="font-semibold">Rp {tax.toLocaleString('id-ID')}</span>
              </div>
              <div className="flex justify-between items-center bg-gradient-to-r from-orange-100 to-amber-100 rounded-lg px-4 py-4 shadow-md border-2 border-orange-300 mt-4">
                <span className="font-bold text-lg text-amber-950">Total</span>
                <span className="text-orange-600 font-bold text-2xl">Rp {total.toLocaleString('id-ID')}</span>
              </div>
            </div>

            <button className="w-full mt-6 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold py-4 rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
              Lanjut ke Pembayaran
            </button>
          </div>
        </aside>
      </div>

      {orderItems.length > 0 && (
        <div className="lg:hidden fixed bottom-0 left-0 w-full bg-gradient-to-r from-amber-50 to-orange-50 p-4 border-t-4 border-orange-500 shadow-2xl backdrop-blur-sm">
          <div className="max-w-screen-xl mx-auto">
            <div className="flex justify-between items-center mb-3">
              <span className="text-amber-950 font-bold text-lg">Total Pesanan:</span>
              <span className="text-orange-600 font-bold text-2xl">Rp {total.toLocaleString('id-ID')}</span>
            </div>
            <button className="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold py-4 rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all shadow-lg active:scale-95">
              Lanjut ke Pembayaran
            </button>
          </div>
        </div>
      )}
    </main>
  );
};

export default OrderPage;