import { BrowserRouter, Routes, Route, Outlet } from 'react-router-dom';
import Navbar from './components/navbar';
import Home from './pages/Home';
import Promo from './pages/Promo';
import Shop from './pages/Shop';
import './css/App.css';

const MainLayout = () => (
  <div className="site-container">
    <Navbar />
    <main className="content-wrap">
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
          <Route path="promo" element={<Promo />} />
          <Route path="shop" element={<Shop />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;