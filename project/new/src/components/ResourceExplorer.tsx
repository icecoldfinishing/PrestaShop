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
    <section className="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
      <div className="card-header bg-white border-bottom py-3">
        <h2 className="card-title fw-bold m-0 fs-4 d-flex align-items-center gap-2">
          <i className="bi bi-box-seam text-primary"></i> Explorateur modules PrestaShop
        </h2>
      </div>
      
      <div className="card-body p-4 bg-light">
        <div className="alert alert-info d-flex align-items-center mb-4 border-0 shadow-sm">
          <i className="bi bi-info-circle-fill me-3 fs-4"></i>
          <div>
            Appel réel API: liste IDs via <code>/api/{resource}</code> puis chargement détail via <code>/api/{resource}/{"{id}"}</code>.
          </div>
        </div>

        <div className="row align-items-center mb-4">
          <div className="col-md-auto">
            <label htmlFor="resource-select" className="form-label fw-bold mb-md-0">
              Module à explorer:
            </label>
          </div>
          <div className="col-md-4 col-sm-8">
            <select
              id="resource-select"
              className="form-select form-select-lg shadow-sm border-0"
              value={resource}
              onChange={(event) => setResource(event.target.value)}
            >
              {DEFAULT_MODULES.map((moduleName) => (
                <option key={moduleName} value={moduleName}>
                  {moduleName.charAt(0).toUpperCase() + moduleName.slice(1)}
                </option>
              ))}
            </select>
          </div>
        </div>

        {loading && (
          <div className="text-center py-4">
            <div className="spinner-border text-primary" role="status">
              <span className="visually-hidden">Chargement...</span>
            </div>
            <p className="text-muted mt-2">Chargement de la liste détaillée...</p>
          </div>
        )}

        {error && (
          <div className="alert alert-danger shadow-sm border-0" role="alert">
            <i className="bi bi-exclamation-triangle-fill me-2"></i> {error}
          </div>
        )}

        {!loading && !error && (
          <div className="bg-white p-3 rounded-3 shadow-sm mb-4 d-inline-flex align-items-center gap-2">
            <i className="bi bi-check-circle-fill text-success"></i>
            <strong>Éléments détaillés chargés :</strong> 
            <span className="badge bg-primary rounded-pill fs-6">{items.length}</span>
          </div>
        )}

        {!loading && !error && selectedItem && (
          <XmlViewer
            title={`Exemple détail (${resource})`}
            data={selectedItem}
            loading={false}
            error={null}
          />
        )}
      </div>
    </section>
  );
}
