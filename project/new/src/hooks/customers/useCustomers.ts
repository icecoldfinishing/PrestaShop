import { useEffect, useState } from "react";

import {
    fetchCustomerList,
    fetchCustomerDetail,
    type CustomerDetail,
    type CustomerItem
} from "../../lib/prestashop/api/customers/customerApi";



interface AsyncState<T> {
    data: T;
    loading: boolean;
    error: string | null;
}

export function useCustomers(page = 1, limit = 12): AsyncState<CustomerItem[]> {
    const [state, setState] = useState<AsyncState<CustomerItem[]>>({
        data: [],
        loading: true,
        error: null
    });

    useEffect(() => {
        let cancelled = false;

        async function run(): Promise<void> {
            setState((prev) => ({ ...prev, loading: true, error: null }));

            try {
                const data = await fetchCustomerList(page, limit);

                if (!cancelled) {
                    setState({ data, loading: false, error: null });
                }
            } catch (error) {
                if (!cancelled) {
                    setState({
                        data: [],
                        loading: false,
                        error: error instanceof Error ? error.message : "Erreur chargement clients"
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

export function useCustomerDetail(customerId: number | null): AsyncState<CustomerDetail | null> {
    const [state, setState] = useState<AsyncState<CustomerDetail | null>>({
        data: null,
        loading: false,
        error: null
    });

    useEffect(() => {
        if (!customerId) {
            setState({ data: null, loading: false, error: null });
            return;
        }

        const resolvedId = customerId;
        let cancelled = false;

        async function run(): Promise<void> {
            setState((prev) => ({ ...prev, loading: true, error: null }));

            try {
                const data = await fetchCustomerDetail(resolvedId);

                if (!cancelled) {
                    setState({ data, loading: false, error: null });
                }
            } catch (error) {
                if (!cancelled) {
                    setState({
                        data: null,
                        loading: false,
                        error: error instanceof Error ? error.message : "Erreur chargement client"
                    });
                }
            }
        }

        void run();

        return () => {
            cancelled = true;
        };
    }, [customerId]);

    return state;
}