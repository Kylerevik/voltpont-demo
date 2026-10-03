# Voltpont Villanyszerelő Kft. – demó weboldal

Egy kitalált pécsi villanyszerelő cég bemutatkozó oldala ajánlatkérő űrlappal. A cég és az adatai mintaadatok, az oldal portfólió célú.

Élő demó: https://voltpont-demo.kesug.com

## Funkciók

- Mobile first, reszponzív oldal; telefonon vízszintesen húzható kártyasorok
- Szolgáltatások, munkamenet, szűrhető referenciák, vélemények, gyakori kérdések
- Ajánlatkérő űrlap kliens- és szerveroldali validációval, mentés MySQL-be
- Alap SEO (title, meta, Open Graph, JSON-LD), szemantikus HTML, billentyűzettel használható

## Technológia

HTML, CSS, JavaScript, Bootstrap 5.3 (helyi másolat), PHP 8.1+ (PDO, prepared statement), MySQL / MariaDB.

## Mappaszerkezet

```
index.html
css/        saját stílus, vendor/ alatt a Bootstrap
js/         saját szkript, vendor/ alatt a Bootstrap
img/        logó és háttérminta
php/        ajanlat.php – az űrlap végpontja
includes/   adatbázis-kapcsolat és beállítások (webről nem elérhető)
sql/        schema.sql – a tábla létrehozása
```

## Futtatás helyben (XAMPP)

1. A mappát másold a `htdocs` alá `voltpont` néven.
2. Indítsd el az Apache-ot és a MySQL-t.
3. A phpMyAdminban hozz létre egy `voltpont` nevű adatbázist, majd importáld bele az `sql/schema.sql` fájlt.
4. Másold le az `includes/config.example.php` fájlt `includes/config.php` néven, és szükség esetén írd át az adatokat (XAMPP-on a `root` felhasználó és az üres jelszó a megszokott).
5. Nyisd meg: `http://localhost/voltpont/`.

## Telepítés tárhelyre

1. Hozz létre adatbázist a tárhely vezérlőpultján, és importáld bele az `sql/schema.sql` fájlt.
2. Az `includes/config.php` fájlban add meg az adatbázis hostnevét, nevét, felhasználóját és jelszavát.
3. A fájlokat FTP-vel töltsd a tárhely webgyökerébe (általában `htdocs`), a mappa tartalmát, nem magát a mappát.

Az `includes/config.php` jelszót tartalmaz, ezért a `.gitignore` kizárja a repóból.

## Továbbfejlesztési ötletek

- Admin felület az ajánlatkérések kezelésére
- E-mail értesítés új kérésről (SMTP-vel)
- Adatkezelési tájékoztató oldal
- Sebességkorlát az űrlapra

## Szerző

chill
