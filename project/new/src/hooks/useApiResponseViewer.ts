import { useCallback, useState } from "react";
import { psGet } from "../services/prestashopApi.service";
import type { PrestashopQueryParams } from "../types/prestashopApi.types";

const DEFAULT_RESOURCE = "customers";
const DEFAULT_PARAMS_TEXT = '{"display":"[id,email]"}';

export function useApiResponseViewer() {
  const [resource, setResource] = useState<string>(DEFAULT_RESOURCE);
  const [resourceId, setResourceId] = useState<string>("");
  const [paramsText, setParamsText] = useState<string>(DEFAULT_PARAMS_TEXT);
  const [formattedResponse, setFormattedResponse] = useState<string>("");
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const [errorMessage, setErrorMessage] = useState<string>("");

  const parseParams = useCallback((): PrestashopQueryParams => {
    if (!paramsText.trim()) {
      return {};
    }

    let parsed: unknown;
    try {
      parsed = JSON.parse(paramsText);
    } catch {
      throw new Error("Le JSON des query params est invalide.");
    }

    if (!parsed || typeof parsed !== "object" || Array.isArray(parsed)) {
      return {};
    }

    return parsed as PrestashopQueryParams;
  }, [paramsText]);

  const handleFetch = useCallback(async () => {
    setErrorMessage("");
    setFormattedResponse("");

    if (!resource.trim()) {
      setErrorMessage("La resource est obligatoire.");
      return;
    }

    setIsLoading(true);
    try {
      const queryParams = parseParams();
      const data = await psGet(resource.trim(), resourceId.trim(), queryParams);
      setFormattedResponse(JSON.stringify(data, null, 2));
    } catch (error: unknown) {
      const nextErrorMessage =
        error instanceof Error ? error.message : "Erreur inconnue pendant l appel API.";
      setErrorMessage(nextErrorMessage);
    } finally {
      setIsLoading(false);
    }
  }, [parseParams, resource, resourceId]);

  return {
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
  };
}
