import { psGet, psPost } from "./prestashopApi.service"; // Adapte le chemin vers ton service
import JSZip from "jszip";

export interface ImportResult {
    success: number;
    failed: number;
}

/* ==========================================
   HELPERS
========================================== */

function getReference(filename: string): string {
    // On retire l'extension et on nettoie les espaces
    const parts = filename.split('/');
    const nameWithExt = parts[parts.length - 1]; // Gère si l'image est dans un sous-dossier du zip
    const dot = nameWithExt.lastIndexOf(".");
    if (dot === -1) return nameWithExt.trim();
    return nameWithExt.substring(0, dot).trim();
}

function isImage(name: string): boolean {
    const lower = name.toLowerCase();
    return (
        lower.endsWith(".jpg") ||
        lower.endsWith(".jpeg") ||
        lower.endsWith(".png") ||
        lower.endsWith(".webp")
    );
}

/**
 * Normalise les collections (réutilisée de ton script d'import)
 */
function normalizeApiCollection<T>(value: T | T[] | undefined | null): T[] {
    if (!value) return [];
    return Array.isArray(value) ? value : [value];
}

/* ==========================================
   RECHERCHE PRODUIT PAR RÉFÉRENCE
========================================== */

async function getProductByReference(reference: string): Promise<number | null> {
    try {
        // Utilisation de ton psGet avec filtre
        const res = await psGet("products", "", {
            "filter[reference]": `[${reference}]`,
            display: "full"
        }) as any;

        const products = normalizeApiCollection(res?.prestashop?.products?.product);

        if (products.length > 0) {
            // On récupère l'ID via ta logique habituelle
            const id = products[0].id;
            return id ? Number(id) : null;
        }
        return null;
    } catch (error) {
        console.error(`Erreur recherche produit ${reference}:`, error);
        return null;
    }
}

/* ==========================================
   UPLOAD IMAGE (Multi-part)
========================================== */

async function uploadImage(productId: number, blob: Blob, filename: string) {
    const formData = new FormData();
    // PrestaShop attend le nom 'image' pour le fichier
    formData.append("image", blob, filename);

    // On utilise ton psPost
    // Note: l'API PrestaShop pour les images est particulière : /images/products/{id}
    return await psPost(`images/products/${productId}`, formData);
}

/* ==========================================
   IMPORTATION PRINCIPALE
========================================== */

export async function importImagesFromZip(
    zipFile: File,
    onProgress?: (done: number, total: number) => void
): Promise<ImportResult> {
    const zip = await JSZip.loadAsync(zipFile);
    
    // On filtre les fichiers qui sont des images et on ignore les fichiers système (__MACOSX etc)
    const files = Object.keys(zip.files).filter(name => isImage(name) && !name.startsWith('__'));

    let success = 0;
    let failed = 0;
    let done = 0;

    console.log(`Début de l'import : ${files.length} images détectées.`);

    for (const filename of files) {
        try {
            const reference = getReference(filename);
            const productId = await getProductByReference(reference);

            if (!productId) {
                console.warn(`❌ Produit non trouvé pour la référence : ${reference}`);
                failed++;
            } else {
                const entry = zip.files[filename];
                const blob = await entry.async("blob");

                await uploadImage(productId, blob, filename);
                console.log(`✅ Image uploadée pour le produit : ${reference} (ID: ${productId})`);
                success++;
            }
        } catch (e) {
            console.error(`❌ Erreur lors de l'import de ${filename}:`, e);
            failed++;
        }

        done++;
        if (onProgress) onProgress(done, files.length);
    }

    return { success, failed };
}