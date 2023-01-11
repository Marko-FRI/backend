BMR - Book My restaurant

Avtorja:
    -   Žan jankovec, 63200114
    -   Marko Adamič, 63200041

Izdelana rešitev je končana spletna in android aplikacija za vnaprejšnje naročanje rezervacije (rezerviranje miz restavracije, če je na voljo), pri katerem lahko izbere menije če želi (za vnaprej pripravljeno hrano). Uporabnik mora imeti narejen račun (s katerim lahko komentira, rezervira, doda restavracijo med priljubljene, oceni, …), če ne lahko samo gleda in išče restavracije, bere komentarje.

Marko Adamič je poskrbel za frontend (sprejem responsev, prikaz podatkov, zgradnjo strukture strani, odzivnost strani, pretvorba v android, validacija, ...).
Žan jankovec je poskrbel za backend (vzpostavitev zalednega sistema, torej od izdelave podatkovne baze - konceptualni model, fizični model, SQL dump, izvedel sem povezavo z laravel – generacija seederjev, factoryjev, modulov, migracij, ...).
Design sva naredila skupaj v figmi.

V wiki predelu je napisana dokumentacija za vzpostavitev, tako za frontend in backend.

Za zagon mobilne aplikacije: 

v backend v .env (za mobile)

APP_URL=http://192.168.0.16:8000

FRONTEND_URL=http://192.168.0.16:9000

SESSION_DOMAIN=192.168.0.16

SANCTUM_STATEFUL_DOMAINS=192.168.0.16:9000


ko zaženeš:
v backend: php artisan serve --host=192.168.0.16

v frontend za mobile: quasar dev -m capacitor -T android (izbereš ip 192.168.0.16)

v brskalnik napišeš: 192.168.0.16:9000


![1](/uploads/8059b7d351c9ad10b7650e930b13675b/1.PNG)

![2](/uploads/441a8f56fd8a2964b0c9a1b3abd77926/2.PNG)

![3](/uploads/6aabd23f98eb315d9f54dd3526b2fbec/3.PNG)

![4](/uploads/9c8897456d1cc180937f312092a311cc/4.PNG)

![5](/uploads/5502517c40402fe4073605af5bbc2606/5.PNG)


Konceptualni, fizični model in SQL dump:

![image](/uploads/76c3ca1823d00742de96500e1b0e425a/image.png)

![image](/uploads/5ffca4af2e0a14ad843201d202f0f962/image.png)

[dump.sql](/uploads/89a4beae5a2bc50c1bd997dbc79fb19a/dump.sql)

API dokumentacija:
```
Ne zavarovane poti (lahko si odjavljen):
    /login => preverjanje podatkov(geslo in gmail) za uspešen login z ustrezno validacijo, ob uspešnem smo prijavljeni (generira se token v pinio)
    /register => preverjanje podatkov (ime, priimek, email, geslo) za uspešno registracijo z ustrezno validacijo, ob uspešnem smo prijavlj(generira se token v pinio)

    /homePage => prva stran, ki se pokaže ob zagonu aplikacije (dobimo X restavracij, Y kategorij z N restavracijami, in M mnenj)
    /homePageMoreReviews => prva stran, ki se pokaže ob zagonu aplikacije (dobimo M dodatnih mnenj)

    /restaurantsFirstLoad => stran, ki se naloži ko želimo gledati, iskati, urejati ali filtrirati po kategoriji restavracije (dobimo X kategorij, Y restavracij in število restavracij - dodatno še pagination)
    /restaurants => enako kot restaurantsFirstLoad, le da dobimo filtrirane podatke (dobimo restavracije, število restavracij, error message)

    /restaurant/{id_restaurant} => ogled ene restavracije in X njenih mnenj, delovnega urnika, gumb za rezervacijo, menije (dobimo podatke o restavraciji, menije, urnik, mnenje, število menijev, število mnenj, slike socialnih omrežij)

    /moreReviews => enako kot restaurant/{id_restaurant}, le da dobimo X več mnenj

    /footerData => dobimo le podatke o footerju strani (dobimo kategorije)

Zavarovane poti z laravel sanctum (moras biti prijavljen):
    /logout =>
    /addReview =>

    /restaurantAvaliability =>
    /reserveRestaurant =>

    /favourite =>

    /profile =>
    /deleteReservation =>

    /moreActiveReservations =>
    /morePastReservations =>

    /checkChangeInPassword =>
    /editProfile =>
    /editProfileImage =>

    /adminRestaurantData =>
    /moreAdminActiveReservations =>
    /moreAdminPastReservations =>
    /deleteAdminReservation =>
```
