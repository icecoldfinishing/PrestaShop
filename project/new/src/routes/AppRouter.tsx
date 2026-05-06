import { BrowserRouter, Routes, Route } from "react-router-dom";

import Layout from "../components/Layout";
import Home from "../pages/home/Home";
import ModulesPage from "../pages/modules/ModulesPage";
import ProductDetailPage from "../pages/products/ProductDetailPage";
import ProductsPage from "../pages/products/ProductsPage";
export default function AppRouter() {
  return (
    <BrowserRouter>
      <Routes>
        <Route element={<Layout />}>
          <Route path="/" element={<Home />} />
          <Route path="/products" element={<ProductsPage />} />
          <Route path="/products/:productId" element={<ProductDetailPage />} />
          <Route path="/modules/:moduleName" element={<ModulesPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}