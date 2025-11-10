import { BrowserRouter, Routes, Route, Outlet } from 'react-router-dom';
import Navbar from './components/navbar';
import Home from './pages/Home';
import Promo from './pages/Promo';
import Menu from './pages/Menu';
import AboutUs from './pages/AboutUs';
import OrderPage from './pages/Order'
import './App.css';

const MainLayout = () => (
  <div className="flex flex-col min-h-screen bg-[#4a372d]">
    <Navbar />
    <main className="flex-grow">
      <Outlet />
    </main>
  </div>
);

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<MainLayout />}>
          <Route index element={<Home />} />
          <Route path='menu' element={<Menu />}/>
          <Route path="promo" element={<Promo />} />
          <Route path="order" element={<OrderPage />} />
          <Route path="about-us" element={<AboutUs />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;