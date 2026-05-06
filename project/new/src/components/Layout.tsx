import { Link, NavLink, Outlet } from "react-router-dom";

export default function Layout() {
  return (
    <div style={{ minHeight: "100vh", background: "#f7f8fa" }}>
      <header style={{ background: "#111827", color: "white", padding: "14px 20px" }}>
        <div style={{ maxWidth: 1200, margin: "0 auto", display: "flex", gap: 20, alignItems: "center" }}>
          <Link to="/" style={{ color: "white", textDecoration: "none", fontWeight: 700 }}>
            PrestaShop Front Office
          </Link>
          <nav style={{ display: "flex", gap: 14 }}>
            <NavLink to="/" style={{ color: "white", textDecoration: "none" }}>
              Accueil
            </NavLink>
            <NavLink to="/products" style={{ color: "white", textDecoration: "none" }}>
              Produits
            </NavLink>
            <NavLink to="/modules/products" style={{ color: "white", textDecoration: "none" }}>
              Modules
            </NavLink>
          </nav>
        </div>
      </header>
      <main style={{ maxWidth: 1200, margin: "0 auto", padding: 20 }}>
        <Outlet />
      </main>
    </div>
  );
}
