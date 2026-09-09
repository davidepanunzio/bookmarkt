<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900">{{ __('Informativa sulla privacy') }}</h2>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6 sm:p-8 space-y-4 text-sm text-gray-700 leading-relaxed">
                <p class="text-xs text-gray-400">Ultimo aggiornamento: {{ now()->format('d/m/Y') }}</p>

                <p>
                    BookMarkt è un progetto realizzato a scopo didattico per l'esame finale ITS Web Developer.
                    Questa pagina descrive, in forma semplificata, quali dati vengono raccolti e come vengono usati.
                </p>

                <h3 class="font-serif font-semibold text-gray-900 text-base pt-2">Dati raccolti</h3>
                <p>
                    In fase di registrazione raccogliamo nome, email e password (salvata in forma cifrata). Durante l'uso del sito
                    vengono memorizzati anche: i libri aggiunti al carrello o ai preferiti, gli ordini effettuati (incluso l'indirizzo
                    di spedizione indicato in fase di checkout), le recensioni scritte e le segnalazioni inviate agli amministratori.
                </p>

                <h3 class="font-serif font-semibold text-gray-900 text-base pt-2">Uso dei dati</h3>
                <p>
                    I dati sono utilizzati esclusivamente per far funzionare le funzionalità del sito (gestione ordini, carrello,
                    recensioni, assistenza clienti) e non vengono condivisi con terze parti né utilizzati a scopo pubblicitario.
                </p>

                <h3 class="font-serif font-semibold text-gray-900 text-base pt-2">I tuoi diritti</h3>
                <p>
                    Puoi consultare e modificare i tuoi dati anagrafici dalla pagina del profilo, ed eliminare il tuo account
                    in qualsiasi momento dalla sezione "Impostazioni account".
                </p>
            </div>

            <a href="{{ url()->previous() }}" class="inline-block mt-6 text-sm text-gray-500 underline">&larr; Torna indietro</a>
        </div>
    </div>
</x-app-layout>
