# Résolution de l’erreur SSL PrestaShop (Docker)

## Problème

Après installation de PrestaShop, accès au back-office impossible avec l’erreur :

```
SSL_ERROR_RX_RECORD_TOO_LONG
```

Cela se produit lorsque :

* PrestaShop force **HTTPS**
* Mais le serveur Docker fonctionne uniquement en **HTTP**

---

##  Cause

Pendant l’installation, l’option **SSL activé** a été cochée.

Résultat :

* Redirection automatique vers `https://localhost:8088`
* Mais aucun certificat SSL n’est configuré → erreur navigateur

---

## Solution

### 1. Accéder au conteneur MySQL

```bash
docker exec -it ps-db mysql -u root -p
```

Mot de passe :

```
admin
```

---

### 2. Sélectionner la base de données

```sql
USE prestashop;
```

---

### 3. Désactiver le SSL

```sql
UPDATE ps_configuration 
SET value = '0' 
WHERE name = 'PS_SSL_ENABLED';

UPDATE ps_configuration 
SET value = '0' 
WHERE name = 'PS_SSL_ENABLED_EVERYWHERE';
```

---

### 4. Vider le cache PrestaShop

```bash
docker exec -it ps-app rm -rf /var/www/html/var/cache/*
```

---

### 5. Redémarrer le conteneur

```bash
docker restart ps-app
```

---

## Accès après correction

Utiliser uniquement :

```
http://localhost:8088
```

 Ne pas utiliser `https://`

---

##  Résumé

| Problème                     | Solution               |
| ---------------------------- | ---------------------- |
| HTTPS activé sans certificat | Désactiver SSL en base |
| Cache Symfony bloqué         | Supprimer `/var/cache` |
| Redirection HTTPS            | Utiliser HTTP          |

---

##  Bonnes pratiques

* En local Docker → **ne pas activer SSL**
* Utiliser HTTPS seulement avec :

  * Nginx / reverse proxy
  * certificat SSL (Let’s Encrypt)

---

##  Résultat

* Accès au back-office fonctionnel
* Plus d’erreur SSL
* Environnement dev stable

---

Front-Office : 
http://localhost:8088/mg/

Back-Office : 
http://localhost:8088/admin065iruftkdnrqu12zsl/