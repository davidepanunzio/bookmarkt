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

- PHP >= 8.2 con estensione `zip` (più `pdo_mysql` per MySQL, oppure `pdo_sqlite` per l'alternativa SQLite — quest'ultima è già inclusa in qualunque installazione PHP standard, nessun'estensione da aggiungere)
- Composer
- Node.js e npm
- MySQL 8 **oppure**, in alternativa, nessun database server: vedi "Piano B" più sotto

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

### Piano B: nessun MySQL disponibile (SQLite)

Se sul PC non c'è un server MySQL configurato (es. niente Laragon/XAMPP), si può usare SQLite: un singolo file, senza installare né configurare nessun database server. Al posto della sezione "database MySQL" sopra:

1. In `.env`, cambia `DB_CONNECTION=mysql` in `DB_CONNECTION=sqlite` ed elimina (o commenta) la riga `DB_DATABASE=bookmarkt` — importante: se resta, Laravel la interpreta come nome del file SQLite e ne crea uno sbagliato nella cartella principale del progetto invece che in `database/`. Le righe `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` non servono più ma non danno problemi se restano.
2. Crea il file del database:
   ```powershell
   New-Item database\database.sqlite -ItemType File   # PowerShell
   ```
   ```bash
   touch database/database.sqlite   # Git Bash / terminale VS Code
   ```
3. Prosegui normalmente da `php artisan migrate --seed` in poi (stessi comandi, nessun'altra differenza).

Verificato che schema, seeder e test funzionano in modo identico con SQLite: per chi visita il sito non cambia nulla.

### Le copertine dei libri non si vedono (problema symlink su Windows)

`php artisan storage:link` crea un **collegamento simbolico** da `public/storage` a `storage/app/public`, che è il modo in cui Laravel espone pubblicamente i file caricati (in questo progetto, le copertine dei libri). Se questo comando fallisce, ogni copertina risulta un'immagine rotta in tutto il sito.

**Il problema**: su Windows, creare un collegamento simbolico richiede privilegi particolari. Se l'account con cui hai fatto accesso non li ha, il comando fallisce con un errore relativo ai permessi (es. "Impossibile creare un collegamento simbolico" / codice errore 1314).

**Come risolvere**, in ordine di preferenza:

1. **Esegui il terminale come amministratore** (tasto destro sull'icona di VS Code o del terminale → "Esegui come amministratore"), poi rilancia `php artisan storage:link`.
2. **In alternativa, attiva la Modalità sviluppatore**: Impostazioni di Windows → Privacy e sicurezza → Per sviluppatori → attiva "Modalità sviluppatore". Permette di creare collegamenti simbolici senza eseguire da amministratore.
3. **Se nessuna delle due è praticabile** (es. PC scolastico senza questi permessi), si può aggirare il problema copiando i file invece di collegarli — non è un vero symlink (se aggiungi nuove copertine dopo, va ripetuto), ma per una demo statica funziona identicamente:
   ```powershell
   Copy-Item -Recurse storage\app\public public\storage
   ```

**Come verificare che abbia funzionato**: apri il catalogo (`/libri`) e controlla che le copertine si vedano. In alternativa, prova ad aprire direttamente `http://127.0.0.1:8000/storage/covers/il-nome-della-rosa.png` nel browser — se funziona, mostra la copertina; se lo storage non è collegato, dà errore 404.

## Credenziali demo

Create automaticamente dal seeder (`php artisan migrate --seed`):

| Ruolo | Email | Password |
|---|---|---|
| Amministratore | `admin@example.com` | `password` |
| Cliente | `cliente@example.com` | `password` |

Il catalogo viene popolato con categorie, autori e libri di esempio; l'utente cliente ha già un ordine e alcune recensioni sono precaricate.

## Recuperare il link di reset password (ambiente locale)

Con `MAIL_MAILER=log` (vedi "Scelte progettuali" più sotto), le email che l'applicazione genererebbe — in questo progetto, solo quella di reset password — non vengono spedite ma scritte in `storage/logs/laravel.log`. Per trovare il link dopo aver richiesto "Password dimenticata?":

1. Apri `storage/logs/laravel.log`
2. Cerca (`Ctrl+F`) `reset-password/` — compare due volte nello stesso blocco (pulsante e link testuale), sono identici
3. Se hai già fatto più tentativi, vai all'**ultima occorrenza in fondo al file**: ogni richiesta aggiunge un nuovo blocco, non sostituisce quello precedente

Il link è già completo di email nel parametro `?email=...` e porta dritto al form per scegliere la nuova password. Scade dopo 60 minuti ed è valido una sola volta.

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
