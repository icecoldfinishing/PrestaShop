import { runCombinationImport as runCombinationImportService } from './CombinationImport.service'

export type CombinationImportRow = {
    reference: string
    specificite: string
    karazany: string
    stock: number
    prix: number | null
}

/* =========================================================
    HEADERS ATTENDUS
========================================================= */

const REQUIRED_HEADERS = [
    'reference',
    'specificité',
    'karazany',
    'stock_initial',
    'prix_vente_ttc',
]

/* =========================================================
    CSV SAFE SPLITTER
========================================================= */

function parseCSVLine(line: string): string[] {
    const result: string[] = []
    let current = ''
    let inQuotes = false
    for (let i = 0; i < line.length; i++) {
        const char = line[i]
        if (char === '"') {
            inQuotes = !inQuotes
        } else if (char === ',' && !inQuotes) {
            result.push(current)
            current = ''
        } else {
            current += char
        }
    }
    result.push(current)
    return result.map(v =>
        v.trim().replace(/^"|"$/g, '')
    )
}

/* =========================================================
    TO NUMBER
========================================================= */

function toNumber(value: string | undefined): number {
    if (!value) return 0
    return parseFloat(
        value
            .replace(',', '.')
            .replace(/[^0-9.-]/g, '')
            .trim()
    ) || 0
}

/* =========================================================
    DATE FORMAT DD/MM/YYYY
========================================================= */

function isValidDateFormat(value: string): boolean {
    if (!value) return false
    const regex =
        /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/
    return regex.test(value)
}

/* =========================================================
    PARSER CSV COMBINATIONS
========================================================= */

export function parseCombinationsCSV(
    file: File
): Promise<CombinationImportRow[]> {
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
                        .map(l => l.trim())
                        .filter(Boolean)

                if (lines.length < 2) {
                    resolve([])
                    return
                }

                /* =====================================================
                    HEADERS
                ===================================================== */
                const headers =
                    parseCSVLine(lines[0])
                        .map(h =>
                            h
                                .trim()
                                .toLowerCase()
                        )
                /* =====================================================
                    VALIDATION COLONNES
                ===================================================== */
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
                const results: CombinationImportRow[] = []
                /* =====================================================
                    LIGNES
                ===================================================== */

                for (let i = 1; i < lines.length; i++) {
                    const values =
                        parseCSVLine(lines[i])
                    const row: Record<string, string> = {}
                    headers.forEach((header, index) => {
                        row[header] = values[index] ?? ''
                    })
                    const stock =
                        toNumber(row['stock_initial'])
                    const prix =
                        row['prix_vente_ttc']
                            ? toNumber(row['prix_vente_ttc'])
                            : null
                    /* =================================================
                        VALIDATION MONTANTS POSITIFS
                    ================================================= */

                    if (stock < 0) {
                        reject(
                            new Error(
                                `Ligne ${i + 1} : stock_initial négatif`
                            )
                        )
                        return
                    }
                    if (
                        prix !== null &&
                        prix < 0
                    ) {
                        reject(
                            new Error(
                                `Ligne ${i + 1} : prix_vente_ttc négatif`
                            )
                        )
                        return
                    }

                    /* =================================================
                        VALIDATION DATE
                        (si colonne date existe)
                    ================================================= */
                    if (
                        row['date'] &&
                        !isValidDateFormat(row['date'])
                    ) {
                        reject(
                            new Error(
                                `Ligne ${i + 1} : format date invalide (${row['date']})`
                            )
                        )
                        return
                    }

                    /* =================================================
                        PUSH
                    ================================================= */

                    results.push({
                        reference:
                            row['reference'] || '',
                        specificite:
                            row['specificité'] || '',
                        karazany:
                            row['karazany'] || '',
                        stock,
                        prix,
                    })
                }
                resolve(results)
            } catch (err) {
                reject(err)
            }
        }
        reader.onerror = reject
        reader.readAsText(file)
    })
}

/* =========================================================
    IMPORT SERVICE
========================================================= */

export async function runCombinationImport(
    file: File,
    addLog: (msg: string) => void
) {
    try {
        addLog('Analyse CSV déclinaisons...')
        const data =
            await parseCombinationsCSV(file)
        if (!data.length) {
            addLog('Aucune déclinaison détectée')
            return
        }
        addLog(`${data.length} déclinaisons détectées`)
        addLog('Début import déclinaisons...')
        await runCombinationImportService(
            data,
            addLog
        )
        addLog('Import déclinaisons terminé')
    } catch (err) {
        const message =
            err instanceof Error
                ? err.message
                : String(err)
        addLog(`ERREUR IMPORT : ${message}`)
    }
}