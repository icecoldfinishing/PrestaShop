import { useState } from "react";
import { Link } from "react-router-dom";
import { useProducts } from "../../hooks/products/useHomeData";

export default function ProductsPage() {
  const [page, setPage] = useState(1);
  const limit = 12;
  const { data: products, loading, error } = useProducts(page, limit);

  return (
    <section className="w-100">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 className="fw-bold mb-1">Catalogue Produits</h1>
          <p className="text-secondary mb-0">Découvrez notre collection depuis l'API PrestaShop.</p>
        </div>
      </div>

      {loading ? (
        <div className="text-center py-5">
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Chargement...</span>
          </div>
        </div>
      ) : null}

      {error ? (
        <div className="alert alert-danger" role="alert">{error}</div>
      ) : null}

      {!loading && !error && (
        <>
          <div className="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 mb-5">
            {products.map((product) => (
              <div className="col" key={product.id}>
                <div className="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                  {product.imageUrl ? (
                    <img
                      src={product.imageUrl}
                      className="card-img-top object-fit-cover"
                      alt={product.name}
                      style={{ height: "220px" }}
                    />
                  ) : (
                    <div
                      className="bg-light d-flex align-items-center justify-content-center text-secondary"
                      style={{ height: "220px" }}
                    >
                      Pas d'image
                    </div>
                  )}
                  <div className="card-body d-flex flex-column">
                    <div className="text-muted small mb-1">{product.reference || "N/A"}</div>
                    <h5 className="card-title text-truncate" title={product.name}>
                      {product.name}
                    </h5>
                    <p
                      className="card-text text-muted small"
                      style={{
                        display: "-webkit-box",
                        WebkitLineClamp: 2,
                        WebkitBoxOrient: "vertical",
                        overflow: "hidden",
                      }}
                    >
                      {product.shortDescription
                        ? product.shortDescription.replace(/(<([^>]+)>)/gi, "")
                        : "Pas de description."}
                    </p>
                    <div className="mt-auto pt-3 d-flex justify-content-between align-items-center">
                      <span className="fw-bold text-primary">
                        {product.price ? `${parseFloat(product.price).toFixed(2)} €` : "-"}
                      </span>
                      <Link to={`/products/${product.id}`} className="btn btn-primary btn-sm">
                        Voir détail
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>

          <div className="d-flex justify-content-center">
            <nav aria-label="Page navigation">
              <ul className="pagination">
                <li className={`page-item ${page === 1 ? "disabled" : ""}`}>
                  <button className="page-link" onClick={() => setPage((p) => Math.max(1, p - 1))}>
                    Précédent
                  </button>
                </li>
                <li className="page-item active">
                  <span className="page-link">{page}</span>
                </li>
                <li className={`page-item ${products.length < limit ? "disabled" : ""}`}>
                  <button className="page-link" onClick={() => setPage((p) => p + 1)}>
                    Suivant
                  </button>
                </li>
              </ul>
            </nav>
          </div>
        </>
      )}
    </section>
  );
}
