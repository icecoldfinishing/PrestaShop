import { psGet, psPost, psWrap } from '../../../utils/prestashop-api';
import { asInt, asText } from '../../../utils/normalize';

function toPrestashopDateTime(value = new Date()) {
	const pad = (n) => String(n).padStart(2, '0');
	return `${value.getFullYear()}-${pad(value.getMonth() + 1)}-${pad(value.getDate())} ${pad(value.getHours())}:${pad(value.getMinutes())}:${pad(value.getSeconds())}`;
}

async function getReasonIdBySign(sign) {
	const reasonsData = await psGet('stock_movement_reasons', '', {
		display: '[id,sign]',
		limit: '100',
	});

	const reasons = reasonsData?.prestashop?.stock_movement_reasons?.stock_movement_reason;
	const list = Array.isArray(reasons) ? reasons : (reasons ? [reasons] : []);
	const match = list.find((reason) => asInt(reason?.sign, 0) === sign);

	if (!match?.id) {
		throw new Error('Aucune raison de mouvement de stock trouvee pour ce sens.');
	}

	return asText(match.id);
}

export async function updateStockByDelta(stockId, delta) {
	const normalizedStockId = asText(stockId);
	if (!normalizedStockId) {
		throw new Error('Stock ID invalide.');
	}

	const numericDelta = Number(delta);
	if (!Number.isFinite(numericDelta)) {
		throw new Error('Variation invalide.');
	}

	if (numericDelta === 0) {
		throw new Error('La variation ne peut pas etre 0.');
	}

	const stockData = await psGet('stock_availables', normalizedStockId);
	const stock = stockData?.prestashop?.stock_available;
	if (!stock?.id) {
		throw new Error('Stock introuvable.');
	}

	const currentQuantity = asInt(stock?.quantity, 0);
	const idProduct = asText(stock?.id_product);
	const idProductAttribute = asText(stock?.id_product_attribute, '0') || '0';

	if (!idProduct) {
		throw new Error('Produit introuvable pour ce stock.');
	}

	const direction = numericDelta > 0 ? 1 : -1;
	const movementQuantity = Math.abs(asInt(numericDelta, 0));
	if (movementQuantity < 1) {
		throw new Error('Variation invalide.');
	}

	const nextQuantity = currentQuantity + numericDelta;
	if (nextQuantity < 0) {
		throw new Error('Stock insuffisant: la quantite ne peut pas devenir negative.');
	}

	const reasonId = await getReasonIdBySign(direction);
	const employeeId = asText(stock?.id_employee, '1') || '1';

	const payload = psWrap('stock_mvt', {
		id_stock: normalizedStockId,
		id_stock_mvt_reason: reasonId,
		id_employee: employeeId,
		physical_quantity: String(movementQuantity),
		sign: String(direction),
		price_te: '0',
		last_wa: '0',
		current_wa: '0',
		referer: '0',
		date_add: toPrestashopDateTime(),
	});

	await psPost('stock_movements', payload);

	const refreshedStockData = await psGet('stock_availables', normalizedStockId);
	const refreshedStock = refreshedStockData?.prestashop?.stock_available;
	const updatedQuantity = asInt(refreshedStock?.quantity, currentQuantity + numericDelta);

	return {
		stockId: normalizedStockId,
		previousQuantity: currentQuantity,
		delta: numericDelta,
		quantity: updatedQuantity,
	};
}
