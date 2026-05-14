import { runOrderImport as runOrderImportService } from './OrderImport.service'

export type OrderRow = {
    date: string
    nom: string
    email: string
    pwd: string
    adresse: string
    achat: string
    etat: string
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
   PARSER CSV ORDERS
========================= */
export function parseOrdersCSV(file: File): Promise<OrderRow[]> {
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

                const results: OrderRow[] = []

                for (let i = 1; i < lines.length; i++) {
                    const values = parseCSVLine(lines[i])

                    const row: Record<string, string> = {}

                    headers.forEach((header, index) => {
                        row[header] = values[index] ?? ''
                    })

                    results.push({
                        date: row['date_commande_client'] || '',
                        nom: row['nom'] || '',
                        email: row['mail_client'] || '',
                        pwd: row['mot_de_passe'] || '',
                        adresse: row['lieu_livraison'] || '',
                        achat: row['produits_commande'] || '',
                        etat: row['statut_commande'] || ''
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
export async function runOrderImport(
    file: File,
    addLog: (msg: string) => void
) {
    addLog('📄 Analyse CSV commandes...')

    const data = await parseOrdersCSV(file)

    if (!data.length) {
        addLog('⚠️ Aucune commande détectée')
        return
    }

    addLog(`🛒 ${data.length} commandes détectées`)

    await runOrderImportService(data, addLog)

    addLog('🎉 Import commandes terminé')
}