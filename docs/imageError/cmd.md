```bash
cd E:\EVAL\1_PrestaShop\project\prestashop
docker cp .\img\p ps_web:/var/www/html/img/
docker exec -it ps_web ls /var/www/html/img/p/1
```