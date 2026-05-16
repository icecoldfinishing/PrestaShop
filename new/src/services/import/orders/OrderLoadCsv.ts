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
   NORMALIZE HEADER
========================= */
function normalizeHeader(header: string): string {
    return header
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[\s_-]+/g, '')
        .trim()
}

/* =========================
   VALID DATE DD/MM/YYYY
========================= */
function isValidDateFormat(value: string): boolean {
    return /^\d{2}\/\d{2}\/\d{4}$/.test(value)
}

/* =========================
   VALID POSITIVE NUMBER
========================= */
function isPositive(value: number): boolean {
    return value >= 0
}

/* =========================
   PARSER CSV ORDERS
========================= */
export function parseOrdersCSV(
    file: File,
    addLog?: (msg: string) => void
): Promise<OrderRow[]> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.onload = (e) => {
            try {
                const text = e.target?.result as string
                if (!text) {
                    resolve([])
                    return
                }
                const lines = text
                    .split(/\r?\n/)
                    .map(l => l.trim())
                    .filter(Boolean)
                if (lines.length < 2) {
                    addLog?.('❌ CSV vide')
                    resolve([])
                    return
                }
                /* =========================
                   HEADERS
                ========================= */
                const rawHeaders =
                    parseCSVLine(lines[0])
                const headers =
                    rawHeaders.map(normalizeHeader)
                const requiredHeaders = [
                    'date',
                    'nom',
                    'email',
                    'pwd',
                    'adresse'
                ]

                /* =========================
                   CHECK HEADER ERRORS
                ========================= */

                const missingHeaders =
                    requiredHeaders.filter(
                        h => !headers.includes(h)
                    )
                if (missingHeaders.length) {
                    addLog?.(
                        `❌ Colonnes invalides : ${missingHeaders.join(', ')}`
                    )
                    addLog?.(
                        `📌 Colonnes attendues : ${requiredHeaders.join(', ')}`
                    )
                    resolve([])
                    return
                }
                const results: OrderRow[] = []
                let hasErrors = false
                /* =========================
                   PARSE ROWS
                ========================= */
                for (let i = 1; i < lines.length; i++) {
                    const values =
                        parseCSVLine(lines[i])
                    const row: Record<string, string> = {}
                    headers.forEach((header, index) => {
                        row[header] = values[index] ?? ''
                    })
                    /* =========================
                       DATE FORMAT CHECK
                    ========================= */
                    if (!isValidDateFormat(row['date'])) {
                        addLog?.(
                            `❌ Ligne ${i + 1} : format date invalide "${row['date']}" (DD/MM/YYYY attendu)`
                        )
                        hasErrors = true
                    }
                    /* =========================
                       EMAIL REQUIRED
                    ========================= */
                    if (!row['email']) {
                        addLog?.(
                            `❌ Ligne ${i + 1} : email vide`
                        )
                        hasErrors = true
                    }
                    results.push({
                        date: row['date'] || '',
                        nom: row['nom'] || '',
                        email: row['email'] || '',
                        pwd: row['pwd'] || '',
                        adresse: row['adresse'] || '',
                        achat: row['achat'] || '',
                        etat: row['etat'] || ''
                    })
                }
                if (hasErrors) {
                    addLog?.(
                        '⛔ Import commandes annulé à cause des erreurs'
                    )
                    resolve([])
                    return
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
    try {
        addLog('📄 Analyse CSV commandes...')
        const data =
            await parseOrdersCSV(file, addLog)
        if (!data.length) {
            addLog('⚠️ Aucune commande valide détectée')
            return
        }
        addLog(`🛒 ${data.length} commandes détectées`)
        await runOrderImportService(
            data,
            addLog
        )
        addLog('🎉 Import commandes terminé')
    } catch (err: any) {
        addLog(
            `❌ Erreur import commandes : ${err?.message || err}`
        )
    }
}
