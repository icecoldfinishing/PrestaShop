import { useState } from "react";
import { Link, useParams } from "react-router-dom";
import { useProductDetail } from "../../hooks/products/useHomeData";

export default function ProductDetailPage() {
  const params = useParams();
  const productId = Number(params.productId);
  const isValidId = Number.isFinite(productId) && productId > 0;
  const { data: product, loading, error } = useProductDetail(isValidId ? productId : null);
  const [addedToCart, setAddedToCart] = useState(false);

  const handleAddToCart = () => {
    setAddedToCart(true);
    setTimeout(() => setAddedToCart(false), 3000);
  };

  if (!isValidId) {
    return (
      <div className="alert alert-warning mt-4">
        ID produit invalide.
      </div>
    );
  }

  return (
    <section className="w-100">
      <nav aria-label="breadcrumb" className="mb-3">
        <ol className="breadcrumb">
          <li className="breadcrumb-item"><Link to="/">Accueil</Link></li>
          <li className="breadcrumb-item"><Link to="/products">Catalogue</Link></li>
          <li className="breadcrumb-item active" aria-current="page">
            {product ? product.name : "Chargement..."}
          </li>
        </ol>
      </nav>

      <Link to="/products" className="btn btn-outline-secondary btn-sm mb-4">
        Retour au catalogue
      </Link>

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

      {product ? (
        <div className="row g-5 mt-0">
          <div className="col-md-5 col-lg-4">
            <div
              className="bg-light rounded-3 d-flex align-items-center justify-content-center overflow-hidden"
              style={{ minHeight: "380px" }}
            >
              {product.imageUrl ? (
                <img
                  src={product.imageUrl}
                  className="img-fluid w-100 h-100 object-fit-contain"
                  alt={product.name}
                />
              ) : (
                <div className="text-secondary text-center p-5">
                  <p className="mt-3">Aucune image disponible</p>
                </div>
              )}
            </div>
          </div>

          <div className="col-md-7 col-lg-8 d-flex flex-column">
            <h1 className="fw-bold mb-2">{product.name}</h1>
            <div className="d-flex align-items-center mb-3 gap-2">
              <span className="badge bg-secondary">Ref: {product.reference || "N/A"}</span>
              {product.active ? (
                <span className="badge bg-success">En stock</span>
              ) : (
                <span className="badge bg-danger">Rupture</span>
              )}
            </div>

            <h2 className="display-6 fw-bold text-primary mb-4">
              {product.price ? `${parseFloat(product.price).toFixed(2)} €` : "Prix sur demande"}
            </h2>

            {addedToCart && (
              <div className="alert alert-success mb-4" role="alert">
                Produit ajouté au panier avec succès !
              </div>
            )}

            <button
              className="btn btn-primary btn-lg mb-4 py-3 fw-bold"
              onClick={handleAddToCart}
              disabled={!product.active}
              style={{ maxWidth: "320px" }}
            >
              Ajouter au panier
            </button>

            <div className="mt-2">
              <h4 className="fw-bold mb-3 border-bottom pb-2">Description</h4>
              <div
                className="text-secondary"
                dangerouslySetInnerHTML={{
                  __html:
                    product.description ||
                    product.shortDescription ||
                    "Aucune description disponible pour ce produit.",
                }}
              />
            </div>
          </div>
        </div>
      ) : null}
    </section>
  );
}
