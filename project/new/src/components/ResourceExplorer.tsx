import { useMemo, useState } from "react";

import { usePrestashopDetailedList } from "../hooks/usePrestashopDetailedList";
import XmlViewer from "./XmlViewer";

const DEFAULT_MODULES = [
  "products",
  "customers",
  "orders",
  "categories",
  "manufacturers",
  "suppliers"
] as const;

export default function ResourceExplorer() {
  const [resource, setResource] = useState<string>(DEFAULT_MODULES[0]);
  const { items, loading, error } = usePrestashopDetailedList(resource, {
    listLimit: 10,
    detailLimit: 5
  });

  const selectedItem = useMemo(() => items[0] ?? null, [items]);

  return (
    <section style={{ border: "1px solid #e2e8f0", borderRadius: 10, padding: 16 }}>
      <h2 style={{ marginTop: 0 }}>Explorateur modules PrestaShop</h2>
      <p style={{ marginTop: 0 }}>
        Appel reel API: liste IDs via <code>/api/{resource}</code> puis chargement detail via{" "}
        <code>/api/{resource}/{"{id}"}</code>.
      </p>

      <div style={{ display: "flex", gap: 12, alignItems: "center", marginBottom: 16 }}>
        <label htmlFor="resource-select">
          <strong>Module:</strong>
        </label>
        <select
          id="resource-select"
          value={resource}
          onChange={(event) => setResource(event.target.value)}
          style={{ padding: "6px 8px", borderRadius: 6 }}
        >
          {DEFAULT_MODULES.map((moduleName) => (
            <option key={moduleName} value={moduleName}>
              {moduleName}
            </option>
          ))}
        </select>
      </div>

      {loading ? <p>Chargement de la liste detaillee...</p> : null}
      {error ? <p style={{ color: "#b42318" }}>{error}</p> : null}

      {!loading && !error ? (
        <div style={{ marginBottom: 12 }}>
          <strong>Elements detailles charges:</strong> {items.length}
        </div>
      ) : null}

      {!loading && !error ? (
        <XmlViewer
          title={`Exemple detail (${resource})`}
          data={selectedItem}
          loading={false}
          error={null}
        />
      ) : null}
    </section>
  );
}
