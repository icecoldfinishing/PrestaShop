import { Link, NavLink, Outlet } from "react-router-dom";

export default function Layout() {
  return (
    <div className="d-flex flex-column min-vh-100">
      <header className="sticky-top">
        <nav className="navbar navbar-expand-lg navbar-dark bg-dark py-2">
          <div className="w-100 px-4">
            <div className="d-flex align-items-center justify-content-between">
              <Link className="navbar-brand fw-bold fs-5" to="/">
                PrestaShop Front Office
              </Link>
              <button
                className="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation"
              >
                <span className="navbar-toggler-icon"></span>
              </button>
              <div className="collapse navbar-collapse" id="navbarNav">
                <ul className="navbar-nav me-auto ms-4">
                  <li className="nav-item">
                    <NavLink
                      className={({ isActive }) => `nav-link px-3 ${isActive ? "active fw-semibold" : ""}`}
                      to="/"
                      end
                    >
                      Accueil
                    </NavLink>
                  </li>
                  <li className="nav-item">
                    <NavLink
                      className={({ isActive }) => `nav-link px-3 ${isActive ? "active fw-semibold" : ""}`}
                      to="/products"
                    >
                      Produits
                    </NavLink>
                  </li>
                  <li className="nav-item">
                    <NavLink
                      className={({ isActive }) => `nav-link px-3 ${isActive ? "active fw-semibold" : ""}`}
                      to="/customers"
                    >
                      Customers
                    </NavLink>
                  </li>
                  <li className="nav-item">
                    <NavLink
                      className={({ isActive }) => `nav-link px-3 ${isActive ? "active fw-semibold" : ""}`}
                      to="/orders"
                    >
                      Orders
                    </NavLink>
                  </li>
                  <li className="nav-item">
                    <NavLink
                      className={({ isActive }) => `nav-link px-3 ${isActive ? "active fw-semibold" : ""}`}
                      to="/modules/products"
                    >
                      Modules
                    </NavLink>
                  </li>
                </ul>
                <div className="d-flex align-items-center">
                  <button className="btn btn-outline-light btn-sm position-relative px-3">
                    Panier
                    <span className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                      0<span className="visually-hidden">produits dans le panier</span>
                    </span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </nav>
      </header>

      <main className="flex-grow-1 w-100 px-4 py-4">
        <Outlet />
      </main>

      <footer className="bg-dark text-white py-3 border-top border-secondary">
        <div className="w-100 px-4 text-center">
          <p className="mb-0 text-secondary small">
            &copy; {new Date().getFullYear()} PrestaShop React Front Office — Bootstrap 5
          </p>
        </div>
      </footer>
    </div>
  );
}

