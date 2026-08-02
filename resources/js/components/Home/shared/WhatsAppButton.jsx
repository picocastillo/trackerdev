import React from "react";

const WHATSAPP_URL =
    "https://wa.me/543425287592?text=Hola,%20Me%20gustaría%20cotizar%20mi%20proyecto%20";

export function WhatsAppLink({
    className = "",
    showLabel = true,
    children = null,
}) {
    return (
        <a
            href={WHATSAPP_URL}
            target="_blank"
            rel="noreferrer"
            className={className}
            aria-label="WhatsApp +54 342 528-7592"
        >
            <i className="fab fa-whatsapp text-lg" aria-hidden="true" />
            {children ?? (showLabel ? <span>WhatsApp</span> : null)}
        </a>
    );
}

export default function WhatsAppButton() {
    return (
        <a
            href={WHATSAPP_URL}
            target="_blank"
            rel="noreferrer"
            aria-label="Escribinos por WhatsApp al +54 342 528-7592"
            className="fixed bottom-4 right-4 z-50 inline-flex min-h-12 min-w-12 items-center justify-center gap-2 rounded-md bg-[#25d366] px-3 py-3 text-sm font-semibold text-white shadow-lg shadow-black/30 transition hover:bg-[#1ebe57] focus:outline-none focus:ring-2 focus:ring-[#25d366]/60 sm:bottom-6 sm:right-6 sm:min-w-0 sm:px-4"
        >
            <i
                className="fab fa-whatsapp text-2xl leading-none"
                aria-hidden="true"
            />
            <span className="hidden sm:inline">WhatsApp</span>
        </a>
    );
}

export { WHATSAPP_URL };
