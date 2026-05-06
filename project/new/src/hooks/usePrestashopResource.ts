import { useEffect, useMemo, useState } from "react";

import { prestashopClient } from "../lib/prestashop";
import type { ResourceQuery } from "../lib/prestashop/types";
import type { XmlObject } from "../lib/xml/xmlParser";

interface UsePrestashopResourceOptions {
  enabled?: boolean;
}

interface UsePrestashopResourceResult {
  data: XmlObject | null;
  rawXml: string | null;
  loading: boolean;
  error: string | null;
}

export function usePrestashopResource(
  resourceName: string,
  query?: ResourceQuery,
  options: UsePrestashopResourceOptions = {}
): UsePrestashopResourceResult {
  const [data, setData] = useState<XmlObject | null>(null);
  const [rawXml, setRawXml] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const queryKey = useMemo(() => JSON.stringify(query ?? {}), [query]);
  const enabled = options.enabled ?? true;

  useEffect(() => {
    if (!enabled) {
      return;
    }

    let cancelled = false;

    async function run(): Promise<void> {
      setLoading(true);
      setError(null);

      try {
        const response = await prestashopClient.getResource(resourceName, query);
        if (!cancelled) {
          setData(response.parsed);
          setRawXml(response.rawXml);
        }
      } catch (caughtError) {
        if (!cancelled) {
          setData(null);
          setRawXml(null);
          setError(caughtError instanceof Error ? caughtError.message : "Unknown error");
        }
      } finally {
        if (!cancelled) {
          setLoading(false);
        }
      }
    }

    void run();

    return () => {
      cancelled = true;
    };
  }, [enabled, query, queryKey, resourceName]);

  return { data, rawXml, loading, error };
}
