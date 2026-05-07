import { prestashopClient } from "../..";
import { extractResourceIds } from "../../resourceUtils";
import { asObject, getFieldNumber, getFieldText } from "../../valueExtractors";
import type { XmlObject } from "../../../xml/xmlParser";

export interface OrderItem {
    id: number;
    reference: string;
    customerId: number | null;
    totalPaid: string;
    status: string;
    dateAdd: string;
}

export interface OrderDetail extends OrderItem {
    payment: string;
    shippingNumber: string;
    valid: boolean;
}

function singularize(resourceName: string): string {
    if (resourceName.endsWith("ies")) {
        return `${resourceName.slice(0, -3)}y`;
    }
    if (resourceName.endsWith("sses")) {
        return resourceName.slice(0, -2);
    }
    if (resourceName.endsWith("s")) {
        return resourceName.slice(0, -1);
    }
    return resourceName;
}

function findDetailNode(resourceName: string, parsed: XmlObject): XmlObject | null {
    const root = asObject(parsed.prestashop);
    if (!root) {
        return null;
    }

    const singular = singularize(resourceName);
    const directSingular = asObject(root[singular]);
    if (directSingular) {
        return directSingular;
    }

    const pluralNode = asObject(root[resourceName]);
    if (!pluralNode) {
        return null;
    }

    return asObject(pluralNode[singular]) ?? pluralNode;
}

function mapOrder(order: XmlObject): OrderDetail {
    const id = getFieldNumber(order, "id") ?? 0;

    const reference = getFieldText(order, "reference");
    const customerId = getFieldNumber(order, "id_customer");
    const totalPaid = getFieldText(order, "total_paid_tax_incl");
    const status = getFieldText(order, "current_state");
    const dateAdd = getFieldText(order, "date_add");

    const payment = getFieldText(order, "payment");
    const shippingNumber = getFieldText(order, "shipping_number");
    const valid = getFieldText(order, "valid") === "1";

    return {
        id,
        reference,
        customerId,
        totalPaid,
        status,
        dateAdd,
        payment,
        shippingNumber,
        valid
    };
}

export async function fetchOrderList(page = 1, limit = 12): Promise<OrderItem[]> {
    const offset = Math.max(0, (page - 1) * limit);

    const list = await prestashopClient.getResource("orders", {
        display: "minimum",
        limit: `${offset},${limit}`
    });

    const ids = extractResourceIds(list.parsed, "orders").slice(0, limit);

    const details = await Promise.all(
        ids.map((id) =>
            prestashopClient.getResourceById("orders", id, { display: "full" })
        )
    );

    return details
        .map((entry) => findDetailNode("orders", entry.parsed))
        .filter((entry): entry is XmlObject => Boolean(entry))
        .map((order) => mapOrder(order));
}

export async function fetchOrderDetail(orderId: number): Promise<OrderDetail> {
    const details = await prestashopClient.getResourceById(
        "orders",
        orderId,
        { display: "full" }
    );

    const order = findDetailNode("orders", details.parsed);

    if (!order) {
        throw new Error(`Commande ${orderId} introuvable.`);
    }

    return mapOrder(order);
}

export async function fetchModuleRecords(resourceName: string, limit = 10): Promise<XmlObject[]> {
    const list = await prestashopClient.getResource(resourceName, {
        display: "minimum",
        limit: `0,${limit}`
    });

    const ids = extractResourceIds(list.parsed, resourceName).slice(0, limit);

    const details = await Promise.all(
        ids.map((id) => prestashopClient.getResourceById(resourceName, id))
    );

    return details
        .map((entry) => findDetailNode(resourceName, entry.parsed))
        .filter((entry): entry is XmlObject => Boolean(entry));
}