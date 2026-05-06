import { Link, useParams } from "react-router-dom";

import { useProductDetail } from "../../hooks/useFrontOfficeData";

export default function ProductDetailPage() {
  const params = useParams();
  const productId = Number(params.productId);
  const isValidId = Number.isFinite(productId) && productId > 0;
  const { data: product, loading, error } = useProductDetail(isValidId ? productId : null);

  if (!isValidId) {
    return <p>ID produit invalide.</p>;
  }

  return (
    <section>
      <Link to="/products">Retour catalogue</Link>
      {loading ? <p>Chargement detail produit...</p> : null}
      {error ? <p style={{ color: "#b42318" }}>{error}</p> : null}
      {product ? (
        <article style={{ background: "white", borderRadius: 10, padding: 18, marginTop: 12, border: "1px solid #e5e7eb" }}>
          <h1 style={{ marginTop: 0 }}>{product.name}</h1>
          <p>
            <strong>Reference:</strong> {product.reference || "-"}
          </p>
          <p>
            <strong>Prix:</strong> {product.price || "-"}
          </p>
          <p>
            <strong>Actif:</strong> {product.active ? "Oui" : "Non"}
          </p>
          <h3>Description courte</h3>
          <p>{product.shortDescription || "Aucune description courte."}</p>
          <h3>Description detaillee</h3>
          <p>{product.description || "Aucune description detaillee."}</p>
        </article>
      ) : null}
    </section>
  );
}
