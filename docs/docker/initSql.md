# export complet de la base

```bash
docker exec -i ps-db mysqldump -u root -padmin prestashop > backup.sql
```

# import restore
```bash
docker exec -i ps-db mysql -u root -padmin prestashop < backup.sql
```