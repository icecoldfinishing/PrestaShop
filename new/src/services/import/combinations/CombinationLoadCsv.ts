import { runCombinationImport as runCombinationImportService } from './CombinationImport.service'

export type CombinationImportRow = {
    reference: string
    specificite: string
    karazany: string
    stock: number
    prix: number | null
}

/* =========================
   CSV SAFE SPLITTER
========================= */
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

/* =========================
   PARSER CSV COMBINATIONS
========================= */
export function parseCombinationsCSV(file: File): Promise<CombinationImportRow[]> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader()

        reader.onload = (e) => {
            try {
                const text = e.target?.result as string
                if (!text) return resolve([])

                const lines = text
                    .split(/\r?\n/)
                    .map(l => l.trim())
                    .filter(Boolean)

                if (lines.length < 2) return resolve([])

                const headers = parseCSVLine(lines[0]).map(h =>
                    h.trim().toLowerCase()
                )

                const results: CombinationImportRow[] = []

                for (let i = 1; i < lines.length; i++) {
                    const values = parseCSVLine(lines[i])

                    const row: Record<string, string> = {}

                    headers.forEach((header, index) => {
                        row[header] = values[index] ?? ''
                    })

                    const stockRaw = row['qte stock']
                    const prixRaw = row['prix final']

                    results.push({
                        reference: row['ref produit'] || '',
                        specificite:
                            row['type variation'] ||
                            row['type variation'] ||
                            '',
                        karazany: row['valeur variation'] || '',

                        stock:
                            stockRaw !== ''
                                ? parseInt(stockRaw, 10)
                                : 0,

                        prix:
                            prixRaw && prixRaw !== ''
                                ? Number(
                                    prixRaw
                                        .replace(',', '.')
                                        .replace(/[^0-9.]/g, '')
                                )
                                : null
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

/* =========================
   IMPORT SERVICE
========================= */

export async function runCombinationImport(
    file: File,
    addLog: (msg: string) => void
) {
    addLog('📄 Analyse CSV déclinaisons...')

    const data = await parseCombinationsCSV(file)

    if (!data.length) {
        addLog('⚠️ Aucune déclinaison détectée')
        return
    }

    addLog(`🧩 ${data.length} déclinaisons détectées`)

    await runCombinationImportService(data, addLog)

    addLog('🎉 Import déclinaisons terminé')
}