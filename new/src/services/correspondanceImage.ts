import axios from "axios";
import JSZip from "jszip";

const API_KEY = import.meta.env.VITE_PRESTASHOP_API_KEY;
const BASE_URL = import.meta.env.VITE_PRESTASHOP_BASE_URL || '/api';

export async function importImagesFromZip(
    zipFile: File,
    onProgress?: (done: number, total: number) => void
) {
    const zip = await JSZip.loadAsync(zipFile);
    
    const files = Object.keys(zip.files).filter(name => {
        const lower = name.toLowerCase();
        return (lower.endsWith('.jpg') || lower.endsWith('.png') || lower.endsWith('.jpeg')) 
               && !name.includes('__MACOSX') && !zip.files[name].dir;
    });

    let found = 0;
    let notFound = 0;
    let done = 0;

    console.log(`%c 🚀 SCAN ZIP : ${files.length} images détectées.`, "color: blue; font-weight: bold;");

    for (const filename of files) {
        // --- LOGIQUE DE RÉFÉRENCE ---
        // Si ton PrestaShop a "T_01" comme référence et ton fichier est "T_01.png"
        // On doit tester les deux cas pour être sûr.
        
        const nameWithExtension = filename.split('/').pop() || filename; 
        const nameWithoutExtension = nameWithExtension.substring(0, nameWithExtension.lastIndexOf(".")).trim();

        // On va tenter de chercher avec le nom EXACT (avec extension) 
        // ET le nom COURT (sans extension)
        const candidates = [nameWithoutExtension, nameWithExtension];

        try {
            let productId = null;
            let matchedRef = "";

            for (const refToTest of candidates) {
                console.log(`🔍 Test de la référence : "${refToTest}"...`);
                
                const response = await axios.get(`${BASE_URL}/products`, {
                    params: {
                        ws_key: API_KEY,
                        "filter[reference]": `[${refToTest}]`,
                        display: "[id,reference]",
                        output_format: 'JSON'
                    }
                });

                const products = response.data?.products;
                const p = Array.isArray(products) ? products[0] : products;

                if (p && p.id) {
                    productId = p.id;
                    matchedRef = refToTest;
                    break; // On a trouvé, on arrête de chercher pour ce fichier
                }
            }

            if (productId) {
                console.log(`%c ✅ TROUVÉ : "${filename}" correspond au Produit ID ${productId} (via ref: ${matchedRef})`, "color: green; font-weight: bold;");
                found++;
            } else {
                console.warn(`%c ❌ ÉCHEC : Aucune correspondance pour "${filename}" (testés: ${candidates.join(' et ')})`, "color: orange");
                notFound++;
            }

        } catch (err: any) {
            console.error(`%c ❗ ERREUR RÉSEAU pour ${filename} :`, "color: red", err.message);
            notFound++;
        }

        done++;
        if (onProgress) onProgress(done, files.length);
    }

    console.log(`%c 🏁 BILAN : ${found} OK / ${notFound} ÉCHECS`, "color: blue; font-weight: bold;");
    return { success: found, failed: notFound };
}