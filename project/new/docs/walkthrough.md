# Walkthrough: Front Office and Back Office Separation

We have successfully implemented the requested structural separation and added administrative capabilities to the PrestaShop frontend application.

## Changes Implemented

### 1. State Management for Back Office Authentication
- **File Modifed:** `src/utils/auth-state.js`
- **Details:** Introduced reactive states `loggedAdmin` and `isAdminLoggedIn` to track the session of the administrator, alongside `setLoggedAdmin` and `adminLogout` functions to manage local storage persistence.

### 2. Back Office Administrator Login
- **File Modifed:** `src/components/auth/login.vue`
- **Details:** Upgraded the login form to utilize hardcoded default credentials (`admin@prestashop.com` / `password`). Upon form submission, it verifies these credentials, sets the admin state, and emits a success event to trigger routing.

### 3. Separation of Front Office (FO) and Back Office (BO)
- **File Modifed:** `src/App.vue`
- **Details:** Refactored the main application shell to include a `mode` switch. 
  - **Front Office (FO):** Features a user-friendly top navigation bar for customers. It limits access to shop features like the product list and the customer authentication component (`auth.vue`).
  - **Back Office (BO):** Presents the established sidebar dashboard. It strictly protects all administrative routes (Dashboard, Products, Customers, Orders, CSV Import, Data Reset) using the newly implemented `loggedAdmin` state. Unauthenticated users attempting to view the BO are redirected to the admin login form.

### 4. Order State Modifications
- **Files Modifed:** `src/utils/prestashop-api.js`, `src/components/order/OrderList.vue`
- **Details:** 
  - Added a new XML API handler `psUpdateOrderState` which dynamically constructs an `order_history` POST request to properly transition an order's status.
  - Enhanced the `OrderList.vue` interface with inline action buttons to quickly transition an order to:
    - **Payé** (Paiement effectué - State ID 2)
    - **Échec** (Échec paiement - State ID 8)
    - **Annulé** (Annulé - State ID 6)
  - The UI provides immediate visual feedback (loading spinners) while the API processes the state change and automatically refreshes the order list upon completion.

> [!TIP]
> To switch between the Front Office and Back Office interfaces, use the dedicated toggle buttons located in the navigation headers of both layouts.
