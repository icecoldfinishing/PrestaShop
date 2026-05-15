// services/ProductImport.ts

import { runImport as runProductImportService } from './ProductImport.service'

export type ProductImportRow = {
    date_availability_produit: string
    nom: string
    reference: string
    prix_ttc: number
    taxe: number
    categorie: string
    prix_achat: number
}
/* =========================================================
    HEADERS ATTENDUS
========================================================= */

const REQUIRED_HEADERS = [
    'date_availability_produit',
    'nom',
    'reference',
    'prix_ttc',
    'taxe',
    'categorie',
    'prix_achat',
]

/* =========================================================
    TO NUMBER
========================================================= */

function toNumber(value: string | undefined): number {
    if (!value) return 0
    return parseFloat(
        value
            .replace('%', '')
            .replace(',', '.')
            .trim()
    ) || 0
}

/* =========================================================
    DATE DD/MM/YYYY
========================================================= */

function isValidDateFormat(value: string): boolean {
    if (!value) return false
    const regex =
        /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/
    return regex.test(value)
}

/* =========================================================
    CSV PARSER
========================================================= */

export function parseProductsCSV(
    file: File
): Promise<ProductImportRow[]> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.onload = (e) => {
            try {
                const text = e.target?.result as string
                if (!text) {
                    resolve([])
                    return
                }
                const lines =
                    text
                        .split(/\r?\n/)
                        .filter(l => l.trim())
                if (lines.length < 2) {
                    resolve([])
                    return
                }
                /* =========================================================
                    HEADERS
                ========================================================= */
                const headers =
                    lines[0]
                        .split(',')
                        .map(h =>
                            h
                                .trim()
                                .toLowerCase()
                        )

                /* =========================================================
                    VALIDATION HEADERS
                ========================================================= */
                const missingHeaders =
                    REQUIRED_HEADERS.filter(
                        h => !headers.includes(h)
                    )
                if (missingHeaders.length) {
                    reject(
                        new Error(
                            `Colonnes invalides : ${missingHeaders.join(', ')}`
                        )
                    )
                    return
                }
                const result: ProductImportRow[] = []
                /* =========================================================
                    LIGNES
                ========================================================= */
                for (let i = 1; i < lines.length; i++) {
                    const line = lines[i].trim()
                    if (!line) continue
                    const matches =
                        line.match(
                            /(".*?"|[^",]+)(?=\s*,|\s*$)/g
                        )
                        || line.split(',')
                    const values =
                        matches.map(v =>
                            v
                                .replace(/^"|"$/g, '')
                                .trim()
                        )
                    const row: Record<string, string> = {}
                    headers.forEach((h, idx) => {
                        row[h] = values[idx] || ''
                    })
                    /* =====================================================
                        VALIDATION DATE
                    ===================================================== */
                    if (
                        !isValidDateFormat(
                            row['date_availability_produit']
                        )
                    ) {
                        reject(
                            new Error(
                                `Ligne ${i + 1} : format date invalide (${row['date_availability_produit']})`
                            )
                        )
                        return
                    }
                    /* =====================================================
                        VALIDATION MONTANTS POSITIFS
                    ===================================================== */
                    const prixTtc =
                        toNumber(row['prix_ttc'])
                    const taxe =
                        toNumber(row['taxe'])
                    const prixAchat =
                        toNumber(row['prix_achat'])
                    if (prixTtc < 0) {
                        reject(
                            new Error(
                                `Ligne ${i + 1} : prix_ttc négatif`
                            )
                        )
                        return
                    }
                    if (taxe < 0) {
                        reject(
                            new Error(
                                `Ligne ${i + 1} : taxe négative`
                            )
                        )
                        return
                    }
                    if (prixAchat < 0) {
                        reject(
                            new Error(
                                `Ligne ${i + 1} : prix_achat négatif`
                            )
                        )
                        return
                    }
                    /* =====================================================
                        PUSH
                    ===================================================== */
                    result.push({
                        date_availability_produit:
                            row['date_availability_produit'] || '',
                        nom:
                            row['nom'] || '',
                        reference:
                            row['reference'] || '',
                        prix_ttc:
                            prixTtc,
                        taxe:
                            taxe,
                        categorie:
                            row['categorie'] || '',
                        prix_achat:
                            prixAchat,
                    })
                }
                resolve(result)
            } catch (err) {
                reject(err)
            }
        }
        reader.onerror = reject
        reader.readAsText(file)
    })
}

/* =========================================================
    IMPORT PRODUITS
========================================================= */

export async function runProductImport(
    file: File,
    addLog: (msg: string) => void
) {
    try {
        addLog('Analyse CSV produits...')
        const products =
            await parseProductsCSV(file)
        if (!products.length) {
            addLog('Aucun produit valide détecté')
            return
        }
        addLog(`${products.length} produits détectés`)
        addLog('Début import produits...')
        await runProductImportService(
            products,
            addLog
        )
        addLog('Import produits terminé')
    } catch (err) {
        const message =
            err instanceof Error
                ? err.message
                : String(err)
        addLog(`ERREUR IMPORT : ${message}`)
    }
}