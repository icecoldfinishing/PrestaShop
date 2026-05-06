import { Link } from "react-router-dom";
import { useProducts } from "../../hooks/useFrontOfficeData";

export default function Home() {
  const { data: featuredProducts, loading, error } = useProducts(1, 4);

  return (
    <section className="w-100">
      <div className="py-5 mb-5 bg-primary text-white">
        <h1 className="display-5 fw-bold mb-3">Boutique Front Office PrestaShop</h1>
        <p className="fs-5 mb-4" style={{ opacity: 0.9 }}>
          Application React TypeScript qui consomme les APIs XML PrestaShop pour afficher un vrai front :
          catalogue, fiche produit, et explorateur modules.
        </p>
        <div className="d-flex gap-3 flex-wrap">
          <Link to="/products" className="btn btn-light btn-lg px-4 fw-semibold">
            Voir catalogue
          </Link>
          <Link to="/modules/products" className="btn btn-outline-light btn-lg px-4">
            Voir modules
          </Link>
        </div>
      </div>

      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2 className="mb-0 fw-bold">Produits mis en avant</h2>
        <Link to="/products" className="text-decoration-none">Voir tout</Link>
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
        <div className="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
          {featuredProducts.map((product) => (
            <div className="col" key={product.id}>
              <div className="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                {product.imageUrl ? (
                  <img
                    src={product.imageUrl}
                    className="card-img-top object-fit-cover"
                    alt={product.name}
                    style={{ height: "200px" }}
                  />
                ) : (
                  <div
                    className="bg-light d-flex align-items-center justify-content-center text-secondary"
                    style={{ height: "200px" }}
                  >
                    Pas d'image
                  </div>
                )}
                <div className="card-body d-flex flex-column">
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
                      : "Description indisponible."}
                  </p>
                  <div className="mt-auto pt-3 d-flex justify-content-between align-items-center">
                    <span className="fw-bold text-primary">
                      {product.price ? `${parseFloat(product.price).toFixed(2)} €` : "-"}
                    </span>
                    <Link to={`/products/${product.id}`} className="btn btn-sm btn-outline-primary">
                      Voir détail
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </section>
  );
}
