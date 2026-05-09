import type { FormEvent } from "react";
import { useApiResponseViewer } from "../../hooks/useApiResponseViewer";
import "./ApiResponseViewer.css";

export function ApiResponseViewer() {
  const {
    resource,
    resourceId,
    paramsText,
    formattedResponse,
    isLoading,
    errorMessage,
    setResource,
    setResourceId,
    setParamsText,
    handleFetch,
  } = useApiResponseViewer();

  const onSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    await handleFetch();
  };

  return (
    <section className="api-viewer">
      <h2>API Response Viewer</h2>

      <form className="viewer-form" onSubmit={onSubmit}>
        <label>
          Resource
          <input
            value={resource}
            onChange={(event) => setResource(event.target.value)}
            type="text"
            placeholder="customers"
          />
        </label>

        <label>
          ID (optionnel)
          <input
            value={resourceId}
            onChange={(event) => setResourceId(event.target.value)}
            type="text"
            placeholder="42"
          />
        </label>

        <label>
          Query params (JSON)
          <textarea
            value={paramsText}
            onChange={(event) => setParamsText(event.target.value)}
            rows={5}
            placeholder='{"display":"[id,email]"}'
          />
        </label>

        <button type="submit" disabled={isLoading}>
          {isLoading ? "Chargement..." : "Charger la reponse API"}
        </button>
      </form>

      {errorMessage ? <p className="error">{errorMessage}</p> : null}
      {formattedResponse ? <pre className="result">{formattedResponse}</pre> : null}
    </section>
  );
}
