# export complet de la base

```bash
docker exec -i ps-db mysqldump -u root -padmin prestashop > backup.sql
```

# import restore
```bash
docker exec -i ps-db mysql -u root -padmin prestashop < backup.sql
```

```bash
INSERT INTO ps_employee (
    id_profile,
    id_lang,
    lastname,
    firstname,
    email,
    passwd,
    active,
    optin
)
VALUES (
    1,
    1,
    'Admin',
    'Test',
    'admin@gmail.com',
    MD5('admin123'),
    1,
    0
);

INSERT INTO ps_employee_shop (id_employee, id_shop)
VALUES (
    (SELECT id_employee FROM ps_employee WHERE email='admin@gmail.com'),
    1
);
'''
