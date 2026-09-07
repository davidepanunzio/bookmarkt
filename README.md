# BookMarkt

Mini e-commerce di libri realizzato come progetto finale per l'esame ITS Web Developer.

**Stack**: Laravel 12, Blade, Tailwind CSS, MySQL, Laravel Breeze (autenticazione), Pest (test automatici).

## Funzionalità

**Catalogo pubblico** (visibile anche senza login)
- Ricerca per titolo o nome autore, filtro per categoria, fascia di prezzo e disponibilità, ordinamento
- Scheda libro con copertina, disponibilità e recensioni

**Utente registrato**
- Carrello e checkout (con controllo disponibilità e decremento automatico dello stock)
- Storico dei propri ordini
- Lista dei preferiti (wishlist)
- Recensioni con voto e commento (una per libro, riscriverla la aggiorna)
- Segnalazioni agli amministratori, anche collegate a un ordine specifico, con possibilità di vedere la risposta

**Amministratore** (ruolo `admin`)
- Dashboard con statistiche (fatturato, ordini per stato, libri più venduti, ultimi ordini)
- Gestione completa del catalogo: libri (con upload copertina), categorie, autori
- Gestione ordini: cambio stato (`in_attesa` → `pagato` → `spedito` → `consegnato`)
- Moderazione recensioni
- Gestione segnalazioni: risposta e cambio stato
- Gestione utenti: elenco, ricerca, promozione/retrocessione di ruolo

## Requisiti

- PHP >= 8.2 con estensioni `pdo_mysql` e `zip`
- Composer
- Node.js e npm
- MySQL 8

## Installazione

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
```

Modifica in `.env` le credenziali del tuo database MySQL (`DB_USERNAME`, `DB_PASSWORD`), poi crea il database:

```sql
CREATE DATABASE bookmarkt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Infine:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Il sito è raggiungibile su `http://127.0.0.1:8000`.

## Credenziali demo

Create automaticamente dal seeder (`php artisan migrate --seed`):

| Ruolo | Email | Password |
|---|---|---|
| Amministratore | `admin@example.com` | `password` |
| Cliente | `cliente@example.com` | `password` |

Il catalogo viene popolato con categorie, autori e libri di esempio; l'utente cliente ha già un ordine e alcune recensioni sono precaricate.

## Test automatici

```bash
php artisan test
```

I test (scritti con [Pest](https://pestphp.com)) girano su un database SQLite in-memory separato (configurato in `phpunit.xml`) e non toccano mai il database MySQL di sviluppo. Coprono: checkout, autorizzazioni per area admin, upsert e permessi sulle recensioni, filtri del catalogo, segnalazioni e gestione utenti.

## Scelte progettuali

Alcune semplificazioni consapevoli, utili da poter motivare in sede d'esame:

- **Un solo autore per libro** (relazione uno-a-molti, non molti-a-molti): un autore può avere più libri, ma non è previsto un libro scritto a più mani.
- **Nessuna eliminazione di utenti**: l'admin può promuovere/retrocedere il ruolo ma non cancellare un account, per non perdere a cascata ordini e recensioni collegate.
- **Un admin non può modificare il proprio ruolo** dal pannello, per evitare di autoescludersi accidentalmente dall'area amministrativa.
- **Risposta alle segnalazioni singola**, non un thread di messaggi: sufficiente per lo scopo del progetto, evita la complessità di una vera messaggistica.
- **Email disattivate**: `MAIL_MAILER=log` di default, le eventuali notifiche finiscono nei log invece che in una casella di posta reale, così il progetto funziona senza configurare un server SMTP.
