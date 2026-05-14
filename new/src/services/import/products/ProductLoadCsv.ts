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

/**
 * Nettoyage des nombres type "12,5" -> 12.5
 */
function toNumber(value: string | undefined): number {
    if (!value) return 0
    return parseFloat(value.replace(',', '.')) || 0
}

/**
 * Parse CSV PRODUITS + mapping automatique des colonnes
 */
export function parseProductsCSV(file: File): Promise<ProductImportRow[]> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader()

        reader.onload = (e) => {
            try {
                const text = e.target?.result as string
                if (!text) return resolve([])

                const lines = text.split(/\r?\n/)
                if (lines.length < 2) return resolve([])

                const headers = lines[0]
                    .split(',')
                    .map(h => h.trim().toLowerCase())

                const result: ProductImportRow[] = []

                for (let i = 1; i < lines.length; i++) {
                    const line = lines[i].trim()
                    if (!line) continue

                    const matches =
                        line.match(/(".*?"|[^",]+)(?=\s*,|\s*$)/g)
                        || line.split(',')

                    const values = matches.map(v =>
                        v.replace(/^"|"$/g, '').trim()
                    )

                    const row: Record<string, string> = {}

                    headers.forEach((h, idx) => {
                        row[h] = values[idx] || ''
                    })

                    result.push({
                        date_availability_produit: row['date_availability_produit'] || '',
                        nom: row['nom'] || '',
                        reference: row['reference'] || '',
                        prix_ttc: toNumber(row['prix_ttc']),
                        taxe: toNumber(row['taxe']?.replace('%', '')),
                        categorie: row['categorie'] || '',
                        prix_achat: toNumber(row['prix_achat'])
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

/**
 * IMPORT PRODUITS (appel API/service)
 */

export async function runProductImport(
    file: File,
    addLog: (msg: string) => void
) {
    addLog('📄 Analyse CSV produits...')

    const products = await parseProductsCSV(file)

    if (!products.length) {
        addLog('⚠️ Aucun produit valide détecté')
        return
    }

    addLog(`📦 ${products.length} produits détectés`)

    await runProductImportService(products, addLog)

    addLog('🎉 Import produits terminé')
}