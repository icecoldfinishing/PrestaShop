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
    <section style={{ border: "1px solid #d9d9d9", borderRadius: 8, padding: 16 }}>
      <h2 style={{ marginTop: 0 }}>{title}</h2>
      {loading ? <p>Chargement...</p> : null}
      {error ? <p style={{ color: "#b42318" }}>{error}</p> : null}
      {!loading && !error && !data ? <p>Aucune donnee.</p> : null}
      {!loading && !error && data ? (
        <table style={{ width: "100%", borderCollapse: "collapse" }}>
          <thead>
            <tr>
              <th style={{ textAlign: "left", borderBottom: "1px solid #eaeaea", padding: "8px 4px" }}>
                Champ
              </th>
              <th style={{ textAlign: "left", borderBottom: "1px solid #eaeaea", padding: "8px 4px" }}>
                Valeur
              </th>
            </tr>
          </thead>
          <tbody>
            {lines.map((line) => (
              <tr key={line.key}>
                <td style={{ borderBottom: "1px solid #f3f3f3", padding: "8px 4px", width: "40%" }}>{line.key}</td>
                <td style={{ borderBottom: "1px solid #f3f3f3", padding: "8px 4px" }}>{line.value}</td>
              </tr>
            ))}
          </tbody>
        </table>
      ) : null}
    </section>
  );
}
