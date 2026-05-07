import { prestashopClient } from "../..";
import { extractResourceIds } from "../../resourceUtils";
import { asObject, getFieldNumber, getFieldText } from "../../valueExtractors";
import type { XmlObject } from "../../../xml/xmlParser";

export interface CustomerItem {
    id: number;
    firstName: string;
    lastName: string;
    email: string;
}

export interface CustomerDetail extends CustomerItem {
    active: boolean;
    dateAdd: string;
    dateUpd: string;
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

function findDetailNode(
    resourceName: string,
    parsed: XmlObject,
): XmlObject | null {
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

function mapCustomer(customer: XmlObject): CustomerDetail {
    const id = getFieldNumber(customer, "id") ?? 0;
    const firstName = getFieldText(customer, "firstname") || "";
    const lastName = getFieldText(customer, "lastname") || "";
    const email = getFieldText(customer, "email");

    const active = getFieldText(customer, "active") === "1";
    const dateAdd = getFieldText(customer, "date_add");
    const dateUpd = getFieldText(customer, "date_upd");

    return {
        id,
        firstName,
        lastName,
        email,
        active,
        dateAdd,
        dateUpd,
    };
}

export async function fetchCustomerList(
    page = 1,
    limit = 12,
): Promise<CustomerItem[]> {
    const offset = Math.max(0, (page - 1) * limit);

    const list = await prestashopClient.getResource("customers", {
        display: "minimum",
        limit: `${offset},${limit}`,
    });

    const ids = extractResourceIds(list.parsed, "customers").slice(0, limit);

    const details = await Promise.all(
        ids.map((id) =>
            prestashopClient.getResourceById("customers", id, { display: "full" }),
        ),
    );

    return details
        .map((entry) => findDetailNode("customers", entry.parsed))
        .filter((entry): entry is XmlObject => Boolean(entry))
        .map((customer) => mapCustomer(customer));
}

export async function fetchCustomerDetail(
    customerId: number,
): Promise<CustomerDetail> {
    const details = await prestashopClient.getResourceById(
        "customers",
        customerId,
        { display: "full" },
    );

    const customer = findDetailNode("customers", details.parsed);

    if (!customer) {
        throw new Error(`Customer ${customerId} introuvable.`);
    }

    return mapCustomer(customer);
}

export async function fetchModuleRecords(
    resourceName: string,
    limit = 10,
): Promise<XmlObject[]> {
    const list = await prestashopClient.getResource(resourceName, {
        display: "minimum",
        limit: `0,${limit}`,
    });

    const ids = extractResourceIds(list.parsed, resourceName).slice(0, limit);

    const details = await Promise.all(
        ids.map((id) => prestashopClient.getResourceById(resourceName, id)),
    );

    return details
        .map((entry) => findDetailNode(resourceName, entry.parsed))
        .filter((entry): entry is XmlObject => Boolean(entry));
}
