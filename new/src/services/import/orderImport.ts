import prestashop from "./prestashop"
import { addStockDelta } from "./stockService"

export interface CsvOrder {
  [key: string]: any
}

/* =====================================================
   CSV HELPERS
===================================================== */

function getValue(row: any, aliases: string[]): string {
  for (const key of Object.keys(row)) {
    const clean = key.toLowerCase().trim()
    if (aliases.includes(clean)) {
      return String(row[key] ?? "").trim()
    }
  }
  return ""
}

const getName = (r: any) => getValue(r, ["nom", "name"])
const getEmail = (r: any) => getValue(r, ["email"])
const getPassword = (r: any) => getValue(r, ["pwd", "password"])
const getAddress = (r: any) => getValue(r, ["adresse", "address"])
const getPurchase = (r: any) => getValue(r, ["achat"])
const getEtat = (r: any) => getValue(r, ["etat", "status", "état"])
const getDate = (r: any) => getValue(r, ["date"])
const customerCache = new Map<string, number>()


function mapEtatToStateId(etat: string): number {
  const s = etat.toLowerCase().trim()

  // Flexible keyword matches
  if (s.includes("livraison") || s.includes("cod")) return 13
  if (s.includes("accept") || s.includes("réussi") || s === "payé") return 2
  if (s.includes("préparation") || s.includes("cours")) return 3
  if (s.includes("expé") || s.includes("route")) return 4
  if (s.includes("livré")) return 5
  if (s.includes("annul")) return 6
  if (s.includes("rembours")) return 7
  if (s.includes("erreur") || s.includes("error") || s.includes("échec")) return 8
  if (s.includes("chèque")) return 1
  if (s.includes("virement")) return 10
  if (s.includes("attente")) return 14

  return 2 // default
}


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
  return { module: "ps_cashondelivery", label: "Cash on delivery (COD)" }
}


function parseOrderDate(raw: string): string {
  if (!raw) return new Date().toISOString().split("T")[0]

  // DD/MM/YYYY  or  DD-MM-YYYY
  const m = raw.match(/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/)
  if (m) return `${m[3]}-${m[2]}-${m[1]}`

  // already YYYY-MM-DD
  if (/^\d{4}-\d{2}-\d{2}$/.test(raw)) return raw

  return new Date().toISOString().split("T")[0]
}


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

function randomString(len = 10): string {
  return Math.random().toString(36).substring(2, 2 + len)
}


async function findCustomerByEmail(email: string): Promise<number | undefined> {
  try {
    const res = await prestashop.get(
      `/customers?filter[email]=[${email}]&display=[id]`
    )

    const xml = res.data

    const matches = [...xml.matchAll(/<id><!\[CDATA\[(\d+)\]\]><\/id>/g)]

    if (matches.length === 0) return undefined

    // take first valid customer
    return Number(matches[0][1])
  } catch (e) {
    console.warn("⚠️ findCustomerByEmail failed:", email)
    return undefined
  }
}
async function createCustomer(name: string, email: string, password: string) {
  const parts = name.trim().split(/\s+/)
  const firstname = parts[0] || "Client"
  const lastname = parts.slice(1).join(" ") || "CSV"

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
  </customer>
</prestashop>`

  const res = await safePost("/customers", xml)
  return extractId(res)
}

async function createAddress(customerId: number, name: string, address: string) {
  const parts = name.trim().split(/\s+/)
  const firstname = parts[0] || "Client"
  const lastname = parts.slice(1).join(" ") || "CSV"

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
  </address>
</prestashop>`

  const res = await safePost("/addresses", xml)
  return extractId(res)
}

async function createCart(customerId: number, addressId: number, orderDate: string) {
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
    <date_add>${orderDate} 10:00:00</date_add>
    <date_upd>${orderDate} 10:00:00</date_upd>
  </cart>
</prestashop>`

  const res = await safePost("/carts", xml)
  return extractId(res)
}


async function getProductByReference(ref: string): Promise<{
  id: number
  price: number
  name: string
  reference: string
  xml: string
} | null> {
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

  return {
    id: Number(idMatch[1]),
    price,
    name,
    reference,
    xml
  }
}

async function getTaxRateFromProduct(productXml: string): Promise<number> {
  try {
    // 1. extract tax rule group from product
    const groupMatch = productXml.match(
      /<id_tax_rules_group[^>]*>\s*<!\[CDATA\[(\d+)\]\]>/
    )

    if (!groupMatch) return 0

    const groupId = Number(groupMatch[1])
    if (!groupId) return 0

    // 2. get tax rules for that group
    const rulesRes = await prestashop.get(
      `/tax_rules?filter[id_tax_rules_group]=[${groupId}]&display=full`
    )

    const rulesXml = rulesRes.data

    // 3. extract tax ID (IMPORTANT: multiple rules possible → take first valid)
    const taxIdMatch = rulesXml.match(
      /<id_tax>\s*<!\[CDATA\[(\d+)\]\]>/
    )

    if (!taxIdMatch) return 0

    const taxId = Number(taxIdMatch[1])

    // 4. fetch tax
    const taxRes = await prestashop.get(`/taxes/${taxId}`)

    const rateMatch = taxRes.data.match(
      /<rate>\s*<!\[CDATA\[(.*?)\]\]>/
    )

    return rateMatch ? parseFloat(rateMatch[1]) : 0
  } catch (err) {
    console.error("Tax fetch failed:", err)
    return 0
  }
}

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

function parsePurchase(purchase: string): { reference: string; qty: number; attribute: string }[] {
  const items: { reference: string; qty: number; attribute: string }[] = []
  if (!purchase) return items

  const normalised = purchase.replace(/\[|\]/g, '')
  const regex = /\(([^;]+);\s*(\d+);\s*([^)]*)\)/g
  let match: any

  while ((match = regex.exec(normalised)) !== null) {
    items.push({
      reference: match[1].replace(/["']+/g, '').trim(),
      qty: Number(match[2]),
      attribute: match[3].replace(/["']+/g, '').trim()
    })
  }
  return items
}


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

  let rows = ""
  for (const item of items) {
    rows += `
        <cart_row>
          <id_product>${item.productId}</id_product>
          <id_product_attribute>${item.attributeId}</id_product_attribute>
          <id_address_delivery>${addressId}</id_address_delivery>
          <quantity>${item.qty}</quantity>
        </cart_row>`
  }

  const cartRowsBlock = `<cart_rows>${rows}
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


async function createOrder(
  orderDate: string,
  cartId: number,
  customerId: number,
  addressId: number,
  stateId: number,
  payment: { module: string; label: string },
  totalProductsHT: number,
  totalProductsTTC: number,
  totalTax: number,
  carrierTaxRate: number,
  secureKey: string,
  items: {
    productId: number
    qty: number
    attributeId: number
    price: number
    taxRate: number
    name: string
    reference: string
  }[]
) {
  let orderRows = ""

  for (const item of items) {
    const unitHT = item.price
    const unitTax = unitHT * item.taxRate / 100
    const unitTTC = unitHT + unitTax

    orderRows += `
      <order_row>
        <product_id>${item.productId}</product_id>
        <product_attribute_id>${item.attributeId}</product_attribute_id>
        <product_quantity>${item.qty}</product_quantity>

        <product_name><![CDATA[${item.name}]]></product_name>
        <product_reference><![CDATA[${item.reference}]]></product_reference>

        <product_price>${unitHT.toFixed(6)}</product_price>

        <unit_price_tax_excl>${unitHT.toFixed(6)}</unit_price_tax_excl>
        <unit_price_tax_incl>${unitTTC.toFixed(6)}</unit_price_tax_incl>
      </order_row>`
  }

  const xml = `
<prestashop>
  <order>
    <id_address_delivery>${addressId}</id_address_delivery>
    <id_address_invoice>${addressId}</id_address_invoice>
    <id_cart>${cartId}</id_cart>
    <id_customer>${customerId}</id_customer>

    <id_currency>1</id_currency>
    <id_lang>1</id_lang>
    <id_carrier>1</id_carrier>

    <module><![CDATA[${payment.module}]]></module>
    <payment><![CDATA[${payment.label}]]></payment>

    <secure_key><![CDATA[${secureKey}]]></secure_key>

    <current_state>13</current_state>

    <valid>1</valid>

    <date_add>${orderDate} 10:00:00</date_add>
    <date_upd>${orderDate} 10:00:00</date_upd>

    <total_products>${totalProductsHT.toFixed(6)}</total_products>
    <total_products_wt>${totalProductsTTC.toFixed(6)}</total_products_wt>

    <total_paid_tax_excl>${totalProductsHT.toFixed(6)}</total_paid_tax_excl>
    <total_paid_tax_incl>${totalProductsTTC.toFixed(6)}</total_paid_tax_incl>
    <total_paid>${totalProductsTTC.toFixed(6)}</total_paid>

    <total_paid_real>${totalProductsTTC.toFixed(6)}</total_paid_real>

    <total_shipping>0.000000</total_shipping>
    <total_shipping_tax_excl>0.000000</total_shipping_tax_excl>
    <total_shipping_tax_incl>0.000000</total_shipping_tax_incl>

    <carrier_tax_rate>${carrierTaxRate.toFixed(3)}</carrier_tax_rate>

    <conversion_rate>1.000000</conversion_rate>

    <id_shop>1</id_shop>
    <id_shop_group>1</id_shop_group>

    <associations>
      <order_rows>
        ${orderRows}
      </order_rows>
    </associations>

  </order>
</prestashop>`

  const res = await safePost("/orders", xml)
  return extractId(res)
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

async function forceOrderDate(orderId: number, orderDate: string) {
  try {
    const res = await prestashop.get(`/orders/${orderId}`)
    let xml = res.data as string

    xml = xml.replace(/ xlink:href="[^"]*"/g, "")

    xml = xml.replace(
      /<date_add><!\[CDATA\[.*?\]\]><\/date_add>/,
      `<date_add><![CDATA[${orderDate} 10:00:00]]></date_add>`
    )

    xml = xml.replace(
      /<date_upd><!\[CDATA\[.*?\]\]><\/date_upd>/,
      `<date_upd><![CDATA[${orderDate} 10:00:00]]></date_upd>`
    )

    await prestashop.put(`/orders/${orderId}`, xml)

    console.log("📅 Forced order date:", orderDate)

  } catch (e) {
    console.warn("Failed to force order date")
  }
}

async function forceCartDate(cartId: number, orderDate: string) {
  try {
    const res = await prestashop.get(`/carts/${cartId}`)
    let xml = res.data as string

    // remove xlink attributes
    xml = xml.replace(/ xlink:href="[^"]*"/g, "")

    // replace date_add
    xml = xml.replace(
      /<date_add><!\[CDATA\[.*?\]\]><\/date_add>/,
      `<date_add><![CDATA[${orderDate} 10:00:00]]></date_add>`
    )

    // replace date_upd
    xml = xml.replace(
      /<date_upd><!\[CDATA\[.*?\]\]><\/date_upd>/,
      `<date_upd><![CDATA[${orderDate} 10:00:00]]></date_upd>`
    )

    await prestashop.put(`/carts/${cartId}`, xml)

    console.log("📅 Forced cart date:", orderDate)

  } catch (e) {
    console.warn("⚠️ Failed to force cart date:", cartId)
  }
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
  const etat = getEtat(row).trim()
  const rawDate = getDate(row)

  const hasEtat = etat !== ""

  const stateId = hasEtat ? mapEtatToStateId(etat) : 0
  const payment = hasEtat
    ? mapEtatToPayment(etat)
    : { module: "", label: "" }

  const orderDate = parseOrderDate(rawDate)

  console.log(`📦 Processing: ${name} <${email}> | etat="${etat}" → state=${stateId} | date=${orderDate}`)

  let customerId = customerCache.get(email)

  if (!customerId) {
    customerId = await findCustomerByEmail(email)

    if (!customerId) {
      customerId = await createCustomer(name, email, password)
      console.log("👤 Customer created:", customerId)
    } else {
      console.log("👤 Customer found:", customerId)
    }

    customerCache.set(email, customerId)
  } else {
    console.log("👤 Customer reused from cache:", customerId)
  }

  // ── Address ───────────────────────────────────────
  const addressId = await createAddress(customerId, name, address)
  console.log("📍 Address created:", addressId)

  // ── Cart ──────────────────────────────────────────
  const cartId = await createCart(customerId, addressId, orderDate)
  console.log("🛒 Cart created:", cartId)

  // ── Products ──────────────────────────────────────
  const itemsRaw = parsePurchase(purchase)
  console.log(`  📋 Parsed ${itemsRaw.length} item(s) from achat field:`, itemsRaw)

  const cartItems: { productId: number; qty: number; attributeId: number; price: number; taxRate: number, name: string; reference: string }[] = []
  let totalProductsHT = 0
  let totalTax = 0
  let totalProductsTTC = 0
  let globalTaxRate = 0

  for (const item of itemsRaw) {
    const product = await getProductByReference(item.reference)

    if (!product) {
      console.warn(`  ⚠️ Product not found in PrestaShop: "${item.reference}" — skipping`)
      continue
    }

    const { id: attributeId, priceImpact } = await getProductAttributeId(product.id, item.attribute)

    const finalUnitPriceHT = product.price + priceImpact

    const taxRate = await getTaxRateFromProduct(product.xml)

    if (taxRate > globalTaxRate) globalTaxRate = taxRate

    const unitTax = finalUnitPriceHT * taxRate / 100
    const unitTTC = finalUnitPriceHT + unitTax

    const lineHT = finalUnitPriceHT * item.qty
    const lineTax = unitTax * item.qty
    const lineTTC = unitTTC * item.qty

    totalProductsHT += lineHT
    totalTax += lineTax
    totalProductsTTC += lineTTC

    console.log(
      `✅ ${item.reference}
      qty=${item.qty}
      tax=${taxRate}%
      unitHT=${finalUnitPriceHT.toFixed(2)}
      lineHT=${lineHT.toFixed(2)}
      lineTax=${lineTax.toFixed(2)}
      lineTTC=${lineTTC.toFixed(2)}`
    )

    cartItems.push({
      productId: product.id,
      qty: item.qty,
      attributeId: attributeId,
      price: finalUnitPriceHT,
      taxRate,
      name: product.name,
      reference: product.reference
    })
  }

  if (!cartItems.length) throw new Error(`Cart is empty for ${email} — no products found (references: ${itemsRaw.map(i => i.reference).join(", ")})`)

  console.log(`  💰 Total products: ${totalProductsHT.toFixed(6)}`)

  // ── Add items to cart ─────────────────────────────
  const secureKey = await addToCart(cartId, customerId, addressId, cartItems)
  console.log("🛒 Items added to cart (secure_key extracted)")
  const cartDate = orderDate // use order date for cart as well
  await forceCartDate(cartId, cartDate)
  if (!hasEtat || stateId === 0) {
    console.log("🛒 No status => cart only:", cartId)
    return 0
  }

  const orderId = await createOrder(
    orderDate,
    cartId,
    customerId,
    addressId,
    stateId,
    payment,
    totalProductsHT,
    totalProductsTTC,
    totalTax,
    globalTaxRate,
    secureKey,
    cartItems
  )

  console.log("✅ ORDER CREATED:", orderId)

  // Log negative stock deltas for imported order
  for (const item of cartItems) {
    try {
      await addStockDelta(item.productId, -item.qty, item.attributeId)
    } catch (e) {
      console.warn("Failed to log stock delta for imported order:", e)
    }
  }

  //await updateOrderState(orderId, stateId)

  //console.log(`✅ ORDER STATUS UPDATED to ${stateId}`)

  await forceOrderDate(orderId, orderDate)


  return orderId
}


export interface ImportResult {
  row: number
  email: string
  success: boolean
  orderId?: number
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
      const orderId = await processRow(row)
      result = { row: done + 1, email, success: true, orderId }
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