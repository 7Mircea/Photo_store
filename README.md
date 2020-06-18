# Photo Store
### Cerinte : 
a) Posibilitatea de logare/creare cont nou. <br/>
b) Posibilitatea de a incarca o fotografie. <br/>
c) Afisarea tuturor imaginilor incarcate, indiferent de user-ul care le-a postat. <br/>

### Implementare
Proiectul a fost realizat în limbajele JavaScript, HTML, CSS, SQL și PHP.
Proiectul are 4 pagini diferite din punctul de vedere al utilizatorului: Sign-up, Sign-in, Users si User.
1. Sign-up pentru a adauga un utilizator nou.
2. Sign-in pentru a intra direct in cont.
3. Users pentru a selecta utilizatorul ale carui poze vrea sa le vada.
4. User unde poate vedea pozele utilizatorului selectat. Daca s-a selectat pe el insusi atunci poate sa si adauge poze.
5. In plus fata de cerintele temei:
- se pot adauga mai mult de o fotografie. 
- se pot afisa afisa fotografiile in functie de user
- este implementat buton de like(chiar daca vizual nu se vede daca s-a dat like sau nu, in baza de date se vede, in tabelul user_image, 
coloana btn_like)
- sunt partial implementate comentariile.
- imaginile unui utilizator sunt afisate in ordinea data de numarul de like-uri si data urcarii pe site

Utilizatorii din baza de date:

|Utilizator  |Parola|
|------------|------|
|mircea      |1234  |
|george      |5678  |
|ionut       |9101  |
|marian      |1121  |
|ionel       |3141  |

