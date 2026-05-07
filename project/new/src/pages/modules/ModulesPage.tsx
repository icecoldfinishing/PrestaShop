import { Link, useParams } from "react-router-dom";

import { useModuleRecords } from "../../hooks/products/useHomeData";
import { getFieldText } from "../../lib/prestashop/valueExtractors";

const MODULES = [
  "products",
  "customers",
  "orders",
  "categories",
  "manufacturers",
  "suppliers",
  "addresses",
  "carts"
];

function getPrimaryFields(moduleName: string): string[] {
  switch (moduleName) {
    case "customers":
      return ["id", "firstname", "lastname", "email"];
    case "orders":
      return ["id", "reference", "current_state", "total_paid", "date_add"];
    case "products":
      return ["id", "reference", "price", "active", "name"];
    default:
      return ["id", "name", "reference", "date_add"];
  }
}

export default function ModulesPage() {
  const params = useParams();
  const moduleName = MODULES.includes(params.moduleName ?? "") ? (params.moduleName as string) : "products";
  const { data, loading, error } = useModuleRecords(moduleName, 8);
  const fields = getPrimaryFields(moduleName);

  return (
    <section>
      <h1 style={{ marginTop: 0 }}>Explorer tous les modules API</h1>
      <p>Chaque module est charge en XML via API PrestaShop, puis transforme pour affichage front.</p>
      <div style={{ display: "flex", gap: 8, flexWrap: "wrap", marginBottom: 14 }}>
        {MODULES.map((entry) => (
          <Link key={entry} to={`/modules/${entry}`} style={{ background: "#111827", color: "white", padding: "6px 10px", borderRadius: 6, textDecoration: "none" }}>
            {entry}
          </Link>
        ))}
      </div>

      {loading ? <p>Chargement module {moduleName}...</p> : null}
      {error ? <p style={{ color: "#b42318" }}>{error}</p> : null}

      {!loading && !error ? (
        <table style={{ width: "100%", borderCollapse: "collapse", background: "white", border: "1px solid #e5e7eb" }}>
          <thead>
            <tr>
              {fields.map((field) => (
                <th key={field} style={{ textAlign: "left", borderBottom: "1px solid #e5e7eb", padding: 8 }}>
                  {field}
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {data.map((row, index) => (
              <tr key={`${moduleName}-${index}`}>
                {fields.map((field) => (
                  <td key={field} style={{ borderBottom: "1px solid #f3f4f6", padding: 8 }}>
                    {getFieldText(row, field) || "-"}
                  </td>
                ))}
              </tr>
            ))}
          </tbody>
        </table>
      ) : null}
    </section>
  );
}
