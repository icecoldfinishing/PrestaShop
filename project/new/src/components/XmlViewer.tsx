import type { XmlObject, XmlValue } from "../lib/xml/xmlParser";

interface XmlViewerProps {
  title: string;
  data: XmlObject | null;
  loading: boolean;
  error: string | null;
}

function renderValue(value: XmlValue): string {
  if (value === null) {
    return "null";
  }
  if (typeof value === "object") {
    return JSON.stringify(value, null, 2);
  }
  return String(value);
}

function flattenObject(
  value: XmlValue,
  prefix = "",
  output: Array<{ key: string; value: string }> = []
): Array<{ key: string; value: string }> {
  if (value === null || typeof value !== "object") {
    output.push({ key: prefix || "value", value: renderValue(value) });
    return output;
  }

  if (Array.isArray(value)) {
    value.forEach((entry, index) => {
      flattenObject(entry, `${prefix}[${index}]`, output);
    });
    return output;
  }

  Object.entries(value).forEach(([key, nested]) => {
    const nextKey = prefix ? `${prefix}.${key}` : key;
    if (nested !== null && typeof nested === "object") {
      flattenObject(nested, nextKey, output);
      return;
    }
    output.push({ key: nextKey, value: renderValue(nested) });
  });

  return output;
}

export default function XmlViewer({ title, data, loading, error }: XmlViewerProps) {
  const lines = data ? flattenObject(data).slice(0, 25) : [];

  return (
    <div className="card shadow-sm border-0 rounded-4 overflow-hidden mt-4">
      <div className="card-header bg-dark text-white py-3">
        <h3 className="card-title fw-bold m-0 fs-5 d-flex align-items-center gap-2">
          <i className="bi bi-filetype-xml text-warning"></i> {title}
        </h3>
      </div>
      
      <div className="card-body p-0">
        {loading && (
          <div className="text-center py-5">
            <div className="spinner-border text-primary" role="status">
              <span className="visually-hidden">Chargement...</span>
            </div>
          </div>
        )}
        
        {error && (
          <div className="alert alert-danger m-4 border-0 shadow-sm" role="alert">
            <i className="bi bi-exclamation-triangle-fill me-2"></i> {error}
          </div>
        )}
        
        {!loading && !error && !data && (
          <div className="text-center text-muted p-5 bg-light">
            <i className="bi bi-inbox fs-1 d-block mb-3"></i>
            Aucune donnée à afficher.
          </div>
        )}

        {!loading && !error && data && (
          <div className="table-responsive m-0">
            <table className="table table-hover table-striped mb-0">
              <thead className="table-light">
                <tr>
                  <th scope="col" className="w-50 px-4 py-3 border-bottom-0">Champ</th>
                  <th scope="col" className="px-4 py-3 border-bottom-0">Valeur</th>
                </tr>
              </thead>
              <tbody className="border-top-0">
                {lines.map((line) => (
                  <tr key={line.key}>
                    <td className="px-4 py-3 font-monospace text-secondary small align-middle">
                      {line.key}
                    </td>
                    <td className="px-4 py-3">
                      <span className="badge bg-light text-dark border text-wrap text-start lh-base" style={{ maxWidth: "100%", wordBreak: "break-all" }}>
                        {line.value}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            {flattenObject(data).length > 25 && (
              <div className="bg-light text-center py-2 text-muted small border-top">
                <em>Affichage limité aux 25 premiers champs.</em>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}
