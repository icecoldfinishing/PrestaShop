import { Link } from "react-router-dom";

import { useProducts } from "../../hooks/useFrontOfficeData";

export default function ProductsPage() {
  const { data: products, loading, error } = useProducts(12);

  return (
    <section>
      <h1 style={{ marginTop: 0 }}>Catalogue Produits</h1>
      <p>Chargement live des produits depuis l'API XML PrestaShop.</p>

      {loading ? <p>Chargement des produits...</p> : null}
      {error ? <p style={{ color: "#b42318" }}>{error}</p> : null}

      <div style={{ display: "grid", gridTemplateColumns: "repeat(auto-fill,minmax(250px,1fr))", gap: 14 }}>
        {products.map((product) => (
          <article key={product.id} style={{ background: "white", borderRadius: 10, padding: 14, border: "1px solid #e5e7eb" }}>
            <h3 style={{ marginTop: 0 }}>{product.name}</h3>
            <p style={{ marginBottom: 6 }}>
              <strong>Reference:</strong> {product.reference || "-"}
            </p>
            <p style={{ marginBottom: 6 }}>
              <strong>Prix:</strong> {product.price || "-"}
            </p>
            <p>{product.shortDescription || "Pas de description courte."}</p>
            <Link to={`/products/${product.id}`}>Voir detail</Link>
          </article>
        ))}
      </div>
    </section>
  );
}
