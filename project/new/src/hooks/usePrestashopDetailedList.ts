import { useEffect, useState } from "react";

import { prestashopClient } from "../lib/prestashop";
import type { XmlObject } from "../lib/xml/xmlParser";

interface UsePrestashopDetailedListOptions {
  listLimit?: number;
  detailLimit?: number;
  enabled?: boolean;
}

interface UsePrestashopDetailedListResult {
  items: XmlObject[];
  loading: boolean;
  error: string | null;
}

export function usePrestashopDetailedList(
  resourceName: string,
  options: UsePrestashopDetailedListOptions = {}
): UsePrestashopDetailedListResult {
  const [items, setItems] = useState<XmlObject[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const enabled = options.enabled ?? true;
    if (!enabled) {
      return;
    }

    let cancelled = false;

    async function run(): Promise<void> {
      setLoading(true);
      setError(null);

      try {
        const responses = await prestashopClient.getResourceListWithDetails(resourceName, {
          listLimit: options.listLimit ?? 8,
          detailLimit: options.detailLimit ?? 5
        });

        if (!cancelled) {
          setItems(responses.map((response) => response.parsed));
        }
      } catch (caughtError) {
        if (!cancelled) {
          setItems([]);
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
  }, [options.detailLimit, options.enabled, options.listLimit, resourceName]);

  return { items, loading, error };
}
