{{--
    Logo "BookMarkt": solo naming in serif grassetto, come nei mockup di riferimento.
    Nessun colore di default: eredita il colore del testo dal contesto (di solito scuro,
    su sfondo chiaro), a meno che chi lo usa non passi esplicitamente una classe "text-*"
    (es. nel footer scuro, dove serve bianco).
--}}
<span {{ $attributes->merge(['class' => 'font-serif font-bold tracking-tight select-none']) }}>
    BookMarkt
</span>
