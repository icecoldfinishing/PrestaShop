import { useEffect, useState } from "react";

import {
    fetchOrderList,
    fetchOrderDetail,
    type OrderItem,
    type OrderDetail
} from "../../lib/prestashop/api/order/orderApi";

interface AsyncState<T> {
    data: T;
    loading: boolean;
    error: string | null;
}

export function useOrders(page = 1, limit = 12): AsyncState<OrderItem[]> {
    const [state, setState] = useState<AsyncState<OrderItem[]>>({
        data: [],
        loading: true,
        error: null
    });

    useEffect(() => {
        let cancelled = false;

        async function run(): Promise<void> {
            setState((prev) => ({ ...prev, loading: true, error: null }));

            try {
                const data = await fetchOrderList(page, limit);

                if (!cancelled) {
                    setState({ data, loading: false, error: null });
                }
            } catch (error) {
                if (!cancelled) {
                    setState({
                        data: [],
                        loading: false,
                        error: error instanceof Error ? error.message : "Erreur chargement commandes"
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

export function useOrderDetail(orderId: number | null): AsyncState<OrderDetail | null> {
    const [state, setState] = useState<AsyncState<OrderDetail | null>>({
        data: null,
        loading: false,
        error: null
    });

    useEffect(() => {
        if (!orderId) {
            setState({ data: null, loading: false, error: null });
            return;
        }

        const resolvedId = orderId;
        let cancelled = false;

        async function run(): Promise<void> {
            setState((prev) => ({ ...prev, loading: true, error: null }));

            try {
                const data = await fetchOrderDetail(resolvedId);

                if (!cancelled) {
                    setState({ data, loading: false, error: null });
                }
            } catch (error) {
                if (!cancelled) {
                    setState({
                        data: null,
                        loading: false,
                        error: error instanceof Error ? error.message : "Erreur chargement commande"
                    });
                }
            }
        }

        void run();

        return () => {
            cancelled = true;
        };
    }, [orderId]);

    return state;
}