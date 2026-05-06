import { useEffect, useState } from "react";

import {
  fetchModuleRecords,
  fetchProductDetail,
  fetchProductList,
  type ProductDetail,
  type ProductItem
} from "../lib/prestashop/frontOfficeApi";
import type { XmlObject } from "../lib/xml/xmlParser";

interface AsyncState<T> {
  data: T;
  loading: boolean;
  error: string | null;
}

export function useProducts(page = 1, limit = 12): AsyncState<ProductItem[]> {
  const [state, setState] = useState<AsyncState<ProductItem[]>>({
    data: [],
    loading: true,
    error: null
  });

  useEffect(() => {
    let cancelled = false;

    async function run(): Promise<void> {
      setState((prev) => ({ ...prev, loading: true, error: null }));
      try {
        const data = await fetchProductList(page, limit);
        if (!cancelled) {
          setState({ data, loading: false, error: null });
        }
      } catch (error) {
        if (!cancelled) {
          setState({
            data: [],
            loading: false,
            error: error instanceof Error ? error.message : "Erreur chargement produits"
          });
        }
      }
    }

    void run();
    return () => {
      cancelled = true;
    };
  }, [page, limit]);

  return state;
}

export function useProductDetail(productId: number | null): AsyncState<ProductDetail | null> {
  const [state, setState] = useState<AsyncState<ProductDetail | null>>({
    data: null,
    loading: false,
    error: null
  });

  useEffect(() => {
    if (!productId) {
      setState({ data: null, loading: false, error: null });
      return;
    }
    const resolvedProductId = productId;

    let cancelled = false;

    async function run(): Promise<void> {
      setState((prev) => ({ ...prev, loading: true, error: null }));
      try {
        const data = await fetchProductDetail(resolvedProductId);
        if (!cancelled) {
          setState({ data, loading: false, error: null });
        }
      } catch (error) {
        if (!cancelled) {
          setState({
            data: null,
            loading: false,
            error: error instanceof Error ? error.message : "Erreur chargement produit"
          });
        }
      }
    }

    void run();
    return () => {
      cancelled = true;
    };
  }, [productId]);

  return state;
}

export function useModuleRecords(moduleName: string, limit = 10): AsyncState<XmlObject[]> {
  const [state, setState] = useState<AsyncState<XmlObject[]>>({
    data: [],
    loading: true,
    error: null
  });

  useEffect(() => {
    let cancelled = false;

    async function run(): Promise<void> {
      setState((prev) => ({ ...prev, loading: true, error: null }));
      try {
        const data = await fetchModuleRecords(moduleName, limit);
        if (!cancelled) {
          setState({ data, loading: false, error: null });
        }
      } catch (error) {
        if (!cancelled) {
          setState({
            data: [],
            loading: false,
            error: error instanceof Error ? error.message : `Erreur chargement module ${moduleName}`
          });
        }
      }
    }

    void run();
    return () => {
      cancelled = true;
    };
  }, [limit, moduleName]);

  return state;
}
