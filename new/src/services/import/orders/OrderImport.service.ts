import type { CsvOrder, ImportResult } from "./OrderImport";
import { importOrders } from "./OrderImport";

export type OrderImportInput = CsvOrder[] | string;

function normalizeInput(input: OrderImportInput, log: (msg: string) => void): CsvOrder[] {
    if (Array.isArray(input)) {
        return input;
    }

    if (typeof input !== "string") {
        log("ERREUR: format de donnees invalide.");
        return [];
    }

    const trimmed = input.trim();
    if (!trimmed) {
        return [];
    }

    try {
        const parsed = JSON.parse(trimmed) as unknown;
        if (Array.isArray(parsed)) {
            return parsed as CsvOrder[];
        }

        if (parsed && typeof parsed === "object") {
            const withOrders = (parsed as { orders?: CsvOrder[] }).orders;
            if (Array.isArray(withOrders)) {
                return withOrders;
            }
            return [parsed as CsvOrder];
        }
    } catch (error: any) {
        log(`ERREUR: donnees brutes non JSON (${error?.message || "parse"}).`);
        return [];
    }

    return [];
}

export async function runOrderImport(
    input: OrderImportInput,
    logCallback?: (message: string) => void
): Promise<ImportResult[]> {
    const log = typeof logCallback === "function" ? logCallback : () => { };
    const rows = normalizeInput(input, log);

    if (!rows.length) {
        log("Aucune commande a importer.");
        return [];
    }

    log(`Import commandes: ${rows.length} ligne(s).`);

    return importOrders(rows, (done, result) => {
        if (result.success) {
            const info = result.orderId && result.orderId > 0 ? `order #${result.orderId}` : "cart only";
            log(`OK [${done}/${rows.length}] ${result.email} -> ${info}`);
        } else {
            log(`KO [${done}/${rows.length}] ${result.email} -> ${result.error || "erreur"}`);
        }
    });
}
