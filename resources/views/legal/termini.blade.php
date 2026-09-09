<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900">{{ __('Termini e condizioni') }}</h2>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6 sm:p-8 space-y-4 text-sm text-gray-700 leading-relaxed">
                <p class="text-xs text-gray-400">Ultimo aggiornamento: {{ now()->format('d/m/Y') }}</p>

                <p>
                    BookMarkt è un progetto dimostrativo realizzato a scopo didattico: gli ordini effettuati non comportano
                    transazioni economiche reali. Queste condizioni descrivono, in forma semplificata, come funziona il negozio.
                </p>

                <h3 class="font-serif font-semibold text-gray-900 text-base pt-2">Ordini e disponibilità</h3>
                <p>
                    Ogni libro mostra la quantità disponibile in magazzino. Al momento del checkout la disponibilità viene
                    verificata di nuovo; se un articolo è stato esaurito nel frattempo, l'ordine non viene confermato.
                </p>

                <h3 class="font-serif font-semibold text-gray-900 text-base pt-2">Pagamento</h3>
                <p>
                    Sono supportati due metodi di pagamento: contrassegno (pagamento alla consegna) e bonifico bancario.
                    Non viene richiesto né elaborato alcun dato di carta di credito.
                </p>

                <h3 class="font-serif font-semibold text-gray-900 text-base pt-2">Recensioni</h3>
                <p>
                    Ogni utente registrato può lasciare una recensione per libro. Le recensioni sono moderabili dagli
                    amministratori e possono essere rimosse se ritenute inappropriate.
                </p>

                <h3 class="font-serif font-semibold text-gray-900 text-base pt-2">Assistenza</h3>
                <p>
                    Per problemi con un ordine o con il sito è possibile inviare una segnalazione dalla propria area
                    personale; un amministratore risponderà direttamente nella stessa pagina della segnalazione.
                </p>
            </div>

            <a href="{{ url()->previous() }}" class="inline-block mt-6 text-sm text-gray-500 underline">&larr; Torna indietro</a>
        </div>
    </div>
</x-app-layout>
