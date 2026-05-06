import { Link } from "react-router-dom";
import { useProducts } from "../../hooks/useFrontOfficeData";

export default function Home() {
  const { data: featuredProducts, loading, error } = useProducts(4);

  return (
    <section>
      <div style={{ background: "white", borderRadius: 12, border: "1px solid #e5e7eb", padding: 18 }}>
        <h1 style={{ marginTop: 0 }}>Boutique Front Office PrestaShop</h1>
        <p>
          Application React TypeScript qui consomme les APIs XML PrestaShop pour afficher un vrai front: catalogue,
          fiche produit, et explorateur modules.
        </p>
        <div style={{ display: "flex", gap: 10 }}>
          <Link to="/products">Voir catalogue</Link>
          <Link to="/modules/products">Voir modules</Link>
        </div>
      </div>

      <h2>Produits mis en avant</h2>
      {loading ? <p>Chargement...</p> : null}
      {error ? <p style={{ color: "#b42318" }}>{error}</p> : null}
      <div style={{ display: "grid", gridTemplateColumns: "repeat(auto-fit,minmax(220px,1fr))", gap: 12 }}>
        {featuredProducts.map((product) => (
          <article key={product.id} style={{ background: "white", borderRadius: 8, border: "1px solid #e5e7eb", padding: 12 }}>
            <h3 style={{ marginTop: 0 }}>{product.name}</h3>
            <p>{product.shortDescription || "Description indisponible."}</p>
            <p>
              <strong>Prix:</strong> {product.price || "-"}
            </p>
            <Link to={`/products/${product.id}`}>Voir detail</Link>
          </article>
        ))}
      </div>
    </section>
  );
}
