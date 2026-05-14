import axios from 'axios';
import JSZip from 'jszip';

const API_KEY = import.meta.env.VITE_PRESTASHOP_API_KEY;
const BASE_URL = import.meta.env.VITE_PRESTASHOP_BASE_URL || '/api';

export interface ImportLog {
    time: string;
    msg: string;
    type: 'info' | 'success' | 'error';
}

/**
 * Service pour gérer l'importation d'images (ZIP ou Unique)
 */
export const ImageImportService = {

    /**
     * Traite un fichier ZIP et upload chaque image vers le produit correspondant
     */
    async processZip(zipFile: File, onLog: (log: string, type?: 'info' | 'success' | 'error') => void) {
        const zip = new JSZip();
        const content = await zip.loadAsync(zipFile);

        // Filtrage des fichiers valides
        const imageFiles = Object.keys(content.files).filter(name =>
            !content.files[name].dir &&
            /\.(jpe?g|png|webp)$/i.test(name) &&
            !name.startsWith('__MACOSX')
        );

        onLog(`📦 ZIP décompressé : ${imageFiles.length} images trouvées.`, 'info');

        for (const fileName of imageFiles) {
            const pureName = fileName.split('/').pop() || fileName;
            const reference = pureName.substring(0, pureName.lastIndexOf(".")).trim();

            try {
                const fileBlob = await content.files[fileName].async("blob");

                // 1. Recherche du produit par référence
                const productId = await this.getProductIdByRef(reference);

                if (!productId) {
                    onLog(`⚠️ Réf "${reference}" introuvable, image sautée.`, 'error');
                    continue;
                }

                // 2. Upload de l'image
                await this.uploadToPrestashop(productId, fileBlob, pureName);
                onLog(`✅ Succès : ${pureName} lié au produit ${productId}`, 'success');

            } catch (err: any) {
                onLog(`❌ Erreur sur ${pureName} : ${err.message}`, 'error');
            }
        }
    },

    /**
     * Trouve l'ID d'un produit via sa référence
     */
    async getProductIdByRef(ref: string): Promise<number | null> {
        const { data } = await axios.get(`${BASE_URL}/products`, {
            params: {
                ws_key: API_KEY,
                "filter[reference]": `[${ref}]`,
                display: "[id]",
                output_format: 'JSON'
            }
        });

        const products = data?.products;
        const product = Array.isArray(products) ? products[0] : products;
        return product?.id ? parseInt(product.id) : null;
    },

    /**
     * Envoie le binaire de l'image à l'API PrestaShop
     */
    async uploadToPrestashop(productId: number, blob: Blob, fileName: string) {
        const formData = new FormData();
        formData.append("image", blob, fileName);

        return axios.post(`${BASE_URL}/images/products/${productId}`, formData, {
            params: { ws_key: API_KEY },
            headers: { 'Accept': 'application/json, text/xml' }
        });
    }
};