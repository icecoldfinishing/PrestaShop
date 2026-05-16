import prestashop from "../../prestashop"

export interface CsvOrder {
  [key: string]: any
}

/* =====================================================
   CSV HELPERS
===================================================== */

function getValue(row: any, aliases: string[]): unknown {
  for (const key of Object.keys(row)) {
    const clean = key.toLowerCase().trim()
    if (aliases.includes(clean)) {
      return row[key]
    }
  }
  return undefined
}

const getName = (r: any) => String(getValue(r, ["nom", "name"]) ?? "").trim()
const getEmail = (r: any) => String(getValue(r, ["email"]) ?? "").trim()
const getPassword = (r: any) => String(getValue(r, ["pwd", "password"]) ?? "").trim()
const getAddress = (r: any) => String(getValue(r, ["adresse", "address"]) ?? "").trim()
const getPurchase = (r: any) => getValue(r, ["achat"])
const getEtat = (r: any) => String(getValue(r, ["etat", "status", "état"]) ?? "").trim()
const getDate = (r: any) => String(getValue(r, ["date"]) ?? "").trim()

const getCleanPaymentMethod = (row: any) => {
  const etat = getEtat(row).toLowerCase();
  if (etat.includes("chèque") || etat.includes("cheque") || etat.includes("check")) {
    return "Cheque";
  }
  return "Paiement par virement bancaire"; 
};

/* =====================================================
   ORDER STATE MAPPING  (French → PrestaShop state ID)
   Default PS states:
     1  = Awaiting check payment
     2  = Payment accepted
     3  = Processing in progress
     4  = Shipped
     5  = Delivered
     6  = Cancelled
     7  = Refunded
     8  = Payment error
     9  = On backorder (paid)
     10 = Awaiting bank wire payment
===================================================== */

function mapEtatToOrderData(etat: string) {
  const s = etat.toLowerCase().trim();
  
  // Initialisation des données par défaut (Virement)
  let stateId = 2; // Paiement accepté par défaut
  let payment = { module: 'ps_wirepayment', label: 'Virement bancaire' };

  // 1. Détection du module et du label selon les mots clés
  if (s.includes("livraison") || s.includes("cod")) {
    stateId = 13;
    payment = { module: 'ps_cashondelivery', label: 'Paiement à la livraison' };
  } else if (s.includes("chèque") || s.includes("cheque") || s.includes("check")) {
    stateId = 1;
    payment = { module: 'ps_checkpayment', label: 'Cheque' }; // "Cheque" sans accent pour match module
  } else if (s.includes("virement")) {
    stateId = 10;
    payment = { module: 'ps_wirepayment', label: 'Virement bancaire' };
  }

  // 2. Ajustement de l'ID d'état selon l'avancement (écrase le défaut si nécessaire)
  if (s.includes("préparation") || s.includes("cours")) stateId = 3;
  if (s.includes("expé") || s.includes("route")) stateId = 4;
  if (s.includes("livré")) stateId = 5;
  if (s.includes("annul")) stateId = 6;
  if (s.includes("rembours")) stateId = 7;
  if (s.includes("erreur") || s.includes("error") || s.includes("échec")) stateId = 8;
  if (s.includes("attente")) stateId = 14;
  if (s === "payé" || s.includes("accept")) stateId = 2;

  return { stateId, payment };
}

/* =====================================================
   PAYMENT MODULE MAPPING
===================================================== */

function mapEtatToPayment(etat: string): { module: string; label: string } {
  const s = etat.toLowerCase().trim()

  if (s.includes("livraison") || s.includes("cod")) {
    return { module: "ps_cashondelivery", label: "Cash on delivery (COD)" }
  }
  if (s.includes("chèque") || s.includes("check")) {
    return { module: "ps_checkpayment", label: "Payment by check" }
  }
  if (s.includes("virement") || s.includes("wire")) {
    return { module: "ps_wirepayment", label: "Bank wire" }
  }

  // default: cheque
  return { module: "ps_checkpayment", label: "Payment by check" }
}

/* =====================================================
   DATE HELPERS  (DD/MM/YYYY → YYYY-MM-DD)
===================================================== */

function parseOrderDate(raw: string): string {
  if (!raw) return new Date().toISOString().split("T")[0]

  // DD/MM/YYYY  or  DD-MM-YYYY
  const m = raw.match(/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/)
  if (m) return `${m[3]}-${m[2]}-${m[1]}`

  // already YYYY-MM-DD
  if (/^\d{4}-\d{2}-\d{2}$/.test(raw)) return raw

  return new Date().toISOString().split("T")[0]
}

/* =====================================================
   DEBUG HELPERS
===================================================== */

async function safePost(url: string, xml: string) {
  try {
    return await prestashop.post(url, xml)
  } catch (e: any) {
    if (e.response && e.response.data) {
      const dataStr = typeof e.response.data === 'string'
        ? e.response.data
        : JSON.stringify(e.response.data)
      console.error(`❌ POST ERROR [${url}]:`, dataStr.substring(0, 2000))
    } else {
      console.error(`❌ POST ERROR [${url}]:`, e.message)
    }
    throw e
  }
}

async function safePut(url: string, xml: string) {
  try {
    return await prestashop.put(url, xml)
  } catch (e: any) {
    if (e.response && e.response.data) {
      const dataStr = typeof e.response.data === 'string'
        ? e.response.data
        : JSON.stringify(e.response.data)
      console.error(`❌ PUT ERROR [${url}]:`, dataStr.substring(0, 2000))
    } else {
      console.error(`❌ PUT ERROR [${url}]:`, e.message)
    }
    throw e
  }
}

function extractId(res: any): number {
  const location = res.headers?.location || res.headers?.Location || ""

  if (location) {
    const id = Number(location.split("/").pop())
    if (!isNaN(id) && id > 0) return id
  }

  const xml = res.data || ""

  // <id><![CDATA[12]]></id>
  const m1 = xml.match(/<id><!\[CDATA\[(\d+)\]\]><\/id>/)
  if (m1) return Number(m1[1])

  // plain <id>12</id>
  const m2 = xml.match(/<id>(\d+)<\/id>/)
  if (m2) return Number(m2[1])

  throw new Error("Cannot extract ID from response")
}

async function getCartSecureKey(cartId: number): Promise<string> {
  if (!cartId) return ''
  const res = await prestashop.get(`/carts/${cartId}`)
  const xml: string = res.data || ''
  const match = xml.match(/<secure_key>(?:<!\[CDATA\[)?(.*?)(?:\]\]>)?<\/secure_key>/)
  return match ? match[1].trim() : ''
}

function randomString(len = 10): string {
  return Math.random().toString(36).substring(2, 2 + len)
}

/* =====================================================
   CUSTOMER
===================================================== */

async function findCustomerByEmail(email: string): Promise<number | null> {
  const res = await prestashop.get(
    `/customers?filter[email]=[${email}]&display=full`
  )

  const match = res.data.match(/<id><!\[CDATA\[(\d+)\]\]><\/id>/)
  return match ? Number(match[1]) : null
}

async function createCustomer(name: string, email: string, password: string, orderDate: string) {
  const parts = name.trim().split(/\s+/)
  const firstname = parts[0] || "Client"
  const lastname = parts.slice(1).join(" ") || parts[0]
  const fullDate = `${orderDate} 10:00:00`

  const xml = `
<prestashop>
  <customer>
    <id_default_group>3</id_default_group>
    <id_lang>1</id_lang>
    <firstname><![CDATA[${firstname}]]></firstname>
    <lastname><![CDATA[${lastname}]]></lastname>
    <email><![CDATA[${email}]]></email>
    <passwd><![CDATA[${password || randomString()}]]></passwd>
    <active>1</active>
    <date_add>${fullDate}</date_add>
    <date_upd>${fullDate}</date_upd>
  </customer>
</prestashop>`

  const res = await safePost("/customers", xml)
  return extractId(res)
}

async function forceCustomerDate(customerId: number, orderDate: string) {
  const getRes = await prestashop.get(`/customers/${customerId}`);
  let xml = getRes.data.replace(/ xlink:href="[^"]*"/g, "");
  const fullDate = `${orderDate} 10:00:00`;
  xml = xml.replace(/<date_add>.*?<\/date_add>/, `<date_add><![CDATA[${fullDate}]]></date_add>`);
  xml = xml.replace(/<date_upd>.*?<\/date_upd>/, `<date_upd><![CDATA[${fullDate}]]></date_upd>`);
  await safePut(`/customers/${customerId}`, xml);
}

/* =====================================================
   ADDRESS
===================================================== */

async function createAddress(customerId: number, name: string, address: string, orderDate: string) {
  const parts = name.trim().split(/\s+/)
  const firstname = parts[0] || "Client"
  const lastname = parts.slice(1).join(" ") || "CSV"
  const fullDate = `${orderDate} 10:00:00`

  const xml = `
<prestashop>
  <address>
    <id_customer>${customerId}</id_customer>
    <id_country>8</id_country>
    <firstname><![CDATA[${firstname}]]></firstname>
    <lastname><![CDATA[${lastname}]]></lastname>
    <alias><![CDATA[Home_${Date.now()}]]></alias>
    <address1><![CDATA[${address}]]></address1>
    <city><![CDATA[Paris]]></city>
    <postcode>75000</postcode>
    <date_add>${fullDate}</date_add>
    <date_upd>${fullDate}</date_upd>
  </address>
</prestashop>`

  const res = await safePost("/addresses", xml)
  return extractId(res)
}

async function forceAddressDate(addressId: number, orderDate: string) {
  const getRes = await prestashop.get(`/addresses/${addressId}`);
  let xml = getRes.data.replace(/ xlink:href="[^"]*"/g, "");
  const fullDate = `${orderDate} 10:00:00`;
  xml = xml.replace(/<date_add>.*?<\/date_add>/, `<date_add><![CDATA[${fullDate}]]></date_add>`);
  xml = xml.replace(/<date_upd>.*?<\/date_upd>/, `<date_upd><![CDATA[${fullDate}]]></date_upd>`);
  await safePut(`/addresses/${addressId}`, xml);
}

/* =====================================================
   CART
===================================================== */

async function createCart(
  customerId: number,
  addressId: number,
  date: string,
  items: { productId: number; qty: number; attributeId: number }[] = []
) {
  const fullDate = `${date} 10:00:00`;
  const rows = items.map((item) => `
    <cart_row>
      <id_product>${item.productId}</id_product>
      <id_product_attribute>${item.attributeId}</id_product_attribute>
      <id_address_delivery>${addressId}</id_address_delivery>
      <quantity>${item.qty}</quantity>
    </cart_row>`).join("")

  const associations = rows
    ? `<associations>
      <cart_rows>
        ${rows}
      </cart_rows>
    </associations>`
    : ''

  const xml = `
<prestashop>
  <cart>
    <id_customer>${customerId}</id_customer>
    <id_address_delivery>${addressId}</id_address_delivery>
    <id_address_invoice>${addressId}</id_address_invoice>
    <id_currency>1</id_currency>
    <id_lang>1</id_lang>
    <id_carrier>1</id_carrier>
    <recyclable>0</recyclable>
    <gift>0</gift>
    <mobile_theme>0</mobile_theme>
    <date_add>${fullDate}</date_add>
    <date_upd>${fullDate}</date_upd>
    ${associations}
  </cart>
</prestashop>`

  const res = await safePost("/carts", xml)
  return extractId(res)
}

async function forceCartDate(cartId: number, orderDate: string) {
  const getRes = await prestashop.get(`/carts/${cartId}`);
  let xml = getRes.data.replace(/ xlink:href="[^"]*"/g, "");
  const fullDate = `${orderDate} 10:00:00`;
  xml = xml.replace(/<date_add>.*?<\/date_add>/, `<date_add><![CDATA[${fullDate}]]></date_add>`);
  xml = xml.replace(/<date_upd>.*?<\/date_upd>/, `<date_upd><![CDATA[${fullDate}]]></date_upd>`);
  await safePut(`/carts/${cartId}`, xml);
}

async function findOpenCartId(customerId: number): Promise<number | null> {
  const res = await prestashop.get(
    `/carts?filter[id_customer]=[${customerId}]&display=[id,date_add]`
  )

  const xml: string = res.data || ""
  const cartIds = [...xml.matchAll(/<id><!\[CDATA\[(\d+)\]\]><\/id>/g)]
    .map(m => Number(m[1]))
    .filter(id => !Number.isNaN(id))

  for (const cartId of cartIds.sort((a, b) => b - a)) {
    const ordersRes = await prestashop.get(
      `/orders?filter[id_cart]=[${cartId}]&display=[id]`
    )
    const hasOrder = /<order>/.test(ordersRes.data || "")
    if (!hasOrder) {
      return cartId
    }
  }

  return null
}

/* =====================================================
   PRODUCT LOOKUP  (by reference)
===================================================== */

async function getProductByReference(ref: string): Promise<{ id: number; price: number; name: string; reference: string } | null> {
  const res = await prestashop.get(
    `/products?filter[reference]=[${ref}]&display=full`
  )

  const xml: string = res.data
  const idMatch = xml.match(/<id><!\[CDATA\[(\d+)\]\]><\/id>/)
  if (!idMatch) return null

  const priceMatch = xml.match(/<price><!\[CDATA\[([\d.]+)\]\]><\/price>/)
  const price = priceMatch ? parseFloat(priceMatch[1]) : 0

  const nameMatch = xml.match(/<name>[\s\S]*?<!\[CDATA\[(.*?)\]\]>[\s\S]*?<\/name>/)
  const name = nameMatch ? nameMatch[1].trim() : "Product"

  const refMatch = xml.match(/<reference><!\[CDATA\[(.*?)\]\]><\/reference>/)
  const reference = refMatch ? refMatch[1].trim() : ref

  return { id: Number(idMatch[1]), price, name, reference }
}

/* =====================================================
   ATTRIBUTE COMBINATION LOOKUP
   Given a productId and an attribute value name (e.g. "kely", "ngoza"),
   returns the id_product_attribute (combination ID) or 0 if not found.
===================================================== */

async function getProductAttributeId(productId: number, attributeName: string): Promise<{ id: number; priceImpact: number }> {
  if (!attributeName) return { id: 0, priceImpact: 0 }

  try {
    const combRes = await prestashop.get(
      `/combinations?filter[id_product]=[${productId}]&display=full`
    )
    const combXml: string = combRes.data
    const combBlocks = [...combXml.matchAll(/<combination>([\s\S]*?)<\/combination>/g)]

    for (const block of combBlocks) {
      const content = block[1]
      const idMatch = content.match(/<id><!\[CDATA\[(\d+)\]\]><\/id>/)
      if (!idMatch) continue
      const combId = Number(idMatch[1])

      const priceMatch = content.match(/<price><!\[CDATA\[([\d.-]+)\]\]><\/price>/)
      const priceImpact = priceMatch ? parseFloat(priceMatch[1]) : 0

      const optionValueIds = [
        ...content.matchAll(/<product_option_value[^>]*>\s*<id>(?:<!\[CDATA\[)?(\d+)(?:\]\]>)?<\/id>\s*<\/product_option_value>/g)
      ].map(m => Number(m[1]))

      for (const ovId of optionValueIds) {
        const ovRes = await prestashop.get(`/product_option_values/${ovId}`)
        const ovXml: string = ovRes.data
        const nameMatch = ovXml.match(/<name>[\s\S]*?<!\[CDATA\[(.*?)\]\]>[\s\S]*?<\/name>/)
        if (nameMatch && nameMatch[1].trim().toLowerCase() === attributeName.trim().toLowerCase()) {
          return { id: combId, priceImpact }
        }
      }
    }
  } catch (e) {
    console.warn("⚠️ Attribute lookup failed:", e)
  }

  return { id: 0, priceImpact: 0 }
}

type PurchaseItem = { reference: string; qty: number; attribute: string }

function normalizePurchaseItem(item: any): PurchaseItem | null {
  if (!item || typeof item !== "object") return null

  const reference = String(item.reference ?? item.ref ?? item.produit ?? "").trim()
  const qty = Number(item.qty ?? item.qte ?? item.quantity ?? 0)
  const attribute = String(item.attribute ?? item.attr ?? item.combinaison ?? item.combination ?? "").trim()

  if (!reference || !Number.isFinite(qty) || qty <= 0) return null
  return { reference, qty, attribute }
}

function parsePurchase(purchase: unknown): PurchaseItem[] {
  const items: PurchaseItem[] = []
  if (!purchase) return items

  if (Array.isArray(purchase)) {
    for (const entry of purchase) {
      if (typeof entry === "string") {
        items.push(...parsePurchase(entry))
        continue
      }
      const normalized = normalizePurchaseItem(entry)
      if (normalized) items.push(normalized)
    }
    return items
  }

  if (typeof purchase === "object") {
    const normalized = normalizePurchaseItem(purchase)
    if (normalized) items.push(normalized)
    return items
  }

  if (typeof purchase !== "string") return items

  const trimmed = purchase.trim()
  if (!trimmed) return items

  if (trimmed.startsWith("[") || trimmed.startsWith("{")) {
    try {
      const parsed = JSON.parse(trimmed)
      return parsePurchase(parsed)
    } catch {
      // fall through to regex parsing
    }
  }

  const normalised = trimmed.replace(/\[|\]/g, "")
  const regex = /\(([^;,)]+)[;,]\s*(\d+)\s*[;,]\s*([^)]*)\)/g
  let match: RegExpExecArray | null

  while ((match = regex.exec(normalised)) !== null) {
    items.push({
      reference: match[1].replace(/["']+/g, "").trim(),
      qty: Number(match[2]),
      attribute: match[3].replace(/["']+/g, "").trim()
    })
  }

  return items
}

/* =====================================================
   ADD PRODUCTS TO CART
   PrestaShop PUT /carts/{id} needs the FULL cart entity.
   Steps:
     1. GET /carts/{id}  — all required fields are populated
     2. Strip xlink:href attributes — PS adds them on GET but rejects on PUT
     3. Replace the <cart_rows> node with our items
     4. PUT the patched XML back
===================================================== */

async function addToCart(
  cartId: number,
  _customerId: number,
  addressId: number,
  items: { productId: number; qty: number; attributeId: number }[]
): Promise<string> {
  const getRes = await prestashop.get(`/carts/${cartId}`)
  let cartXml: string = getRes.data

  const skMatch = cartXml.match(/<secure_key>(?:<!\[CDATA\[)?(.*?)(?:\]\]>)?<\/secure_key>/)
  const secureKey = skMatch ? skMatch[1].trim() : ""

  cartXml = cartXml.replace(/ xlink:href="[^"]*"/g, "")

  const existingMatch = cartXml.match(/<cart_rows[^>]*>([\s\S]*?)<\/cart_rows>/)
  const existingRows = existingMatch?.[1] || ""

  const rowRegex = /<cart_row>[\s\S]*?<id_product>(?:<!\[CDATA\[)?(\d+)(?:\]\]>)?<\/id_product>[\s\S]*?<id_product_attribute>(?:<!\[CDATA\[)?(\d+)(?:\]\]>)?<\/id_product_attribute>[\s\S]*?<quantity>(?:<!\[CDATA\[)?(\d+)(?:\]\]>)?<\/quantity>[\s\S]*?<\/cart_row>/g
  const merged = new Map<string, { productId: number; attributeId: number; qty: number }>()

  let match: RegExpExecArray | null
  while ((match = rowRegex.exec(existingRows)) !== null) {
    const productId = Number(match[1])
    const attributeId = Number(match[2])
    const qty = Number(match[3])
    const key = `${productId}-${attributeId}`
    merged.set(key, { productId, attributeId, qty: Number.isFinite(qty) ? qty : 0 })
  }

  for (const item of items) {
    const key = `${item.productId}-${item.attributeId}`
    const current = merged.get(key)
    if (current) {
      current.qty += item.qty
    } else {
      merged.set(key, { productId: item.productId, attributeId: item.attributeId, qty: item.qty })
    }
  }

  let combinedRows = ""
  for (const row of merged.values()) {
    combinedRows += `
        <cart_row>
          <id_product>${row.productId}</id_product>
          <id_product_attribute>${row.attributeId}</id_product_attribute>
          <id_address_delivery>${addressId}</id_address_delivery>
          <quantity>${row.qty}</quantity>
        </cart_row>`
  }

  const cartRowsBlock = `<cart_rows>${combinedRows}
      </cart_rows>`

  if (/<cart_rows[^>]*\/>/.test(cartXml)) {
    cartXml = cartXml.replace(/<cart_rows[^>]*\/>/, cartRowsBlock)
  } else if (/<cart_rows[^>]*>/.test(cartXml)) {
    cartXml = cartXml.replace(/<cart_rows[^>]*>[\s\S]*?<\/cart_rows>/, cartRowsBlock)
  } else if (/<associations>/.test(cartXml)) {
    cartXml = cartXml.replace(/<\/associations>/, `  ${cartRowsBlock}\n    </associations>`)
  } else {
    cartXml = cartXml.replace(/<\/cart>/, `  <associations>\n      ${cartRowsBlock}\n    </associations>\n  </cart>`)
  }

  console.log("🧾 PUT cart XML preview:\n", cartXml.slice(0, 800))

  await safePut(`/carts/${cartId}`, cartXml)

  return secureKey
}

async function forceOrderDate(orderId: number, orderDate: string) {
  // Récupérer d'abord la commande pour avoir le XML complet (sécurité PrestaShop)
  const getRes = await prestashop.get(`/orders/${orderId}`);
  let xml = getRes.data.replace(/ xlink:href="[^"]*"/g, "");

  const fullDate = `${orderDate} 10:00:00`;

  // Remplacer les dates dans le XML
  xml = xml.replace(/<date_add>.*?<\/date_add>/, `<date_add><![CDATA[${fullDate}]]></date_add>`);
  xml = xml.replace(/<date_upd>.*?<\/date_upd>/, `<date_upd><![CDATA[${fullDate}]]></date_upd>`);

  await safePut(`/orders/${orderId}`, xml);
}
/* =====================================================
   ORDER CREATION
===================================================== */
async function createOrder(
  cartId: number,
  customerId: number,
  addressId: number,
  stateId: number,
  payment: { module: string; label: string },
  totalProducts: number,
  secureKey: string,
  items: { productId: number; qty: number; attributeId: number; price: number; name: string; reference: string }[],
  orderDate: string 
) {
  const tp = totalProducts.toFixed(6);
  const totalPaidReal = (stateId === 2 || stateId === 5 || stateId === 11) ? tp : "0.000000";

  let orderRows = "";
  for (const item of items) {
    orderRows += `
      <order_row>
        <product_id>${item.productId}</product_id>
        <product_attribute_id>${item.attributeId}</product_attribute_id>
        <product_quantity>${item.qty}</product_quantity>
        <product_name><![CDATA[${item.name}]]></product_name>
        <product_reference><![CDATA[${item.reference}]]></product_reference>
        <product_price>${item.price.toFixed(6)}</product_price>
        <unit_price_tax_incl>${item.price.toFixed(6)}</unit_price_tax_incl>
        <unit_price_tax_excl>${item.price.toFixed(6)}</unit_price_tax_excl>
      </order_row>`;
  }

  const fullDate = `${orderDate} 10:00:00`; 

  const xml = `
<prestashop>
  <order>
    <id_address_delivery>${addressId}</id_address_delivery>
    <id_address_invoice>${addressId}</id_address_invoice>
    <id_cart>${cartId}</id_cart>
    <id_currency>1</id_currency> <id_lang>1</id_lang>
    <id_customer>${customerId}</id_customer>
    <id_carrier>1</id_carrier>
    <current_state>${stateId}</current_state>
    <module><![CDATA[${payment.module}]]></module>
    <payment><![CDATA[${payment.label}]]></payment>
    <total_paid>${tp}</total_paid>
    <total_paid_real>${totalPaidReal}</total_paid_real>
    <total_products>${tp}</total_products>
    <total_products_wt>${tp}</total_products_wt>
    <conversion_rate>1.000000</conversion_rate>
    <secure_key><![CDATA[${secureKey}]]></secure_key>
    <date_add>${fullDate}</date_add>
    <date_upd>${fullDate}</date_upd>
    <associations>
      <order_rows>
        ${orderRows}
      </order_rows>
    </associations>
  </order>
</prestashop>`;

  const res = await safePost("/orders", xml);
  return extractId(res);
}

async function updateOrderState(orderId: number, stateId: number) {
  const xml = `
<prestashop>
  <order_history>
    <id_order>${orderId}</id_order>
    <id_order_state>${stateId}</id_order_state>
  </order_history>
</prestashop>`
  await safePost("/order_histories", xml)
}

/* =====================================================
   MAIN PROCESS
===================================================== */

async function processRow(row: CsvOrder) {
  const name = getName(row)
  const email = getEmail(row)
  const password = getPassword(row)
  const address = getAddress(row)
  const purchase = getPurchase(row)
  const etat = getEtat(row)
  const rawDate = getDate(row)

  const hasEtat = etat !== ""

  // 1. Préparation des dates et données finales
  const orderDate = parseOrderDate(rawDate) // Ex: "2024-05-13"
  const finalStateId = hasEtat ? mapEtatToOrderData(etat).stateId : 0
  const finalPayment = hasEtat ? mapEtatToPayment(etat) : { module: "", label: "" }

  console.log(`📦 Processing: ${name} <${email}> | Date cible: ${orderDate}`)

  // ── Client ──────────────────────────────────────
  let customerId = await findCustomerByEmail(email)
  if (!customerId) {
    // AJOUT: On passe orderDate pour le date_add du client
    customerId = await createCustomer(name, email, password, orderDate)
    await forceCustomerDate(customerId, orderDate)
    console.log("👤 Client créé avec date:", customerId)
  } else {
    console.log("👤 Client trouvé:", customerId)
  }

  // ── Adresse ───────────────────────────────────────
  // AJOUT: On passe orderDate pour le date_add de l'adresse
  const addressId = await createAddress(customerId, name, address, orderDate)
  await forceAddressDate(addressId, orderDate)
  console.log("📍 Adresse créée avec date:", addressId)

  // ── Produits & Calcul du Total ─────────────────────
  const itemsRaw = parsePurchase(purchase)
  if (!itemsRaw.length) {
    console.log("👤 Client et adresse créés. Aucun article à importer.")
    return { orderId: 0, type: 'customer' }
  }

  const cartItems: { productId: number; qty: number; attributeId: number; price: number; name: string; reference: string }[] = []
  let totalProducts = 0

  for (const item of itemsRaw) {
    const product = await getProductByReference(item.reference)
    if (!product) continue

    const { id: attributeId, priceImpact } = await getProductAttributeId(product.id, item.attribute)
    const finalUnitPrice = product.price + priceImpact
    totalProducts += (finalUnitPrice * item.qty)

    cartItems.push({
      productId: product.id,
      qty: item.qty,
      attributeId: attributeId,
      price: finalUnitPrice,
      name: product.name,
      reference: product.reference
    })
  }

  if (itemsRaw.length > 0 && !cartItems.length) {
    throw new Error(`Aucun produit valide trouvé dans l'achat pour ${email}`)
  }

  // ── Panier ──────────────────────────────────────────
  // Si pas d'etat: on reutilise un panier ouvert. Si etat: on cree un nouveau panier pour isoler.
  let cartId: number | null = null
  let createdNewCart = false

  if (!hasEtat) {
    cartId = await findOpenCartId(customerId)
  }

  if (cartId) {
    console.log("🛒 Panier existant réutilisé:", cartId)
  } else {
    cartId = await createCart(customerId, addressId, orderDate, cartItems)
    await forceCartDate(cartId, orderDate)
    createdNewCart = true
    console.log("🛒 Panier créé avec date:", cartId)
  }

  // ── Ajout au panier (si panier deja existant) ─────────────────────────────
  let secureKey = ''
  if (cartId) {
    if (createdNewCart) {
      secureKey = await getCartSecureKey(cartId)
    } else {
      secureKey = await addToCart(cartId, customerId, addressId, cartItems)
    }
  }

  if (!hasEtat) {
    console.log("🛒 Pas d'état => Fin au panier:", cartId)
    return { orderId: 0, type: 'cart' }
  }

  /**
   * ÉTAPE A : Création de la commande technique
   * On utilise l'état 10 et ps_checkpayment pour bloquer le bug du double paiement.
   */
  const orderId = await createOrder(
    cartId,
    customerId,
    addressId,
    10, 
    { module: "ps_checkpayment", label: "Import Initial" }, 
    totalProducts,
    secureKey,
    cartItems,
    orderDate
  )

  /**
   * ÉTAPE B : Forçage de la date de commande
   * Indispensable car PrestaShop écrase souvent la date au moment du POST.
   */
  await forceOrderDate(orderId, orderDate)
  console.log(`⏰ Date commande forcée au ${orderDate}`)

  /**
   * ÉTAPE C : Validation finale
   * On applique le vrai état et le vrai module.
   */
  await updateOrderState(orderId, finalStateId, finalPayment)

  console.log(`✅ IMPORT RÉUSSI : Commande #${orderId} finalisée.`)

  return { orderId, type: 'order' }
}
/* =====================================================
   IMPORT LOOP
===================================================== */

export interface ImportResult {
  row: number
  email: string
  success: boolean
  orderId?: number
  type?: 'order' | 'cart' | 'customer'
  error?: string
}

export async function importOrders(
  data: CsvOrder[],
  onProgress?: (done: number, result: ImportResult) => void
): Promise<ImportResult[]> {
  const results: ImportResult[] = []
  let done = 0

  for (const row of data) {
    const email = getEmail(row)
    let result: ImportResult

    try {
      const res = await processRow(row)
      const orderId = typeof res === 'number' ? res : (res as any).orderId
      const type = typeof res === 'object' ? (res as any).type : (orderId > 0 ? 'order' : 'cart')
      result = { row: done + 1, email, success: true, orderId, type }
    } catch (e: any) {
      const msg = e?.response?.data || e?.message || String(e)
      console.error(`❌ Row ${done + 1} failed [${email}]:`, msg)
      result = { row: done + 1, email, success: false, error: msg }
    }

    results.push(result)
    done++
    onProgress?.(done, result)
  }

  return results
}
