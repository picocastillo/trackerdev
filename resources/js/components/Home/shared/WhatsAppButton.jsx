import React from "react";

const WHATSAPP_PHONE = "543425287592";
const WHATSAPP_DISPLAY = "+54 342 528-7592";
const WHATSAPP_MESSAGE =
    "Hola, me gustaría cotizar un proyecto con TrackerDev.";

const WHATSAPP_URL = `https://wa.me/${WHATSAPP_PHONE}?text=${encodeURIComponent(WHATSAPP_MESSAGE)}`;

const baseLinkClass =
    "group inline-flex items-center gap-3 rounded-md bg-[#25d366] font-semibold text-white shadow-md shadow-black/20 transition duration-300 hover:-translate-y-0.5 hover:bg-[#1ebe57] hover:shadow-lg hover:shadow-[#25d366]/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#25d366]/70 focus-visible:ring-offset-2 focus-visible:ring-offset-brand-dark";

export function WhatsAppLink({
    className = "",
    variant = "cta",
    showPhone = true,
    label = "Escribinos por WhatsApp",
    children = null,
    ...props
}) {
    const content =
        children ??
        (variant === "compact" ? (
            <>
                <i
                    className="fab fa-whatsapp text-xl leading-none"
                    aria-hidden="true"
                />
                <span>WhatsApp</span>
            </>
        ) : (
            <>
                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-white/15 text-2xl transition group-hover:bg-white/25">
                    <i className="fab fa-whatsapp" aria-hidden="true" />
                </span>
                <span className="flex min-w-0 flex-col items-start text-left leading-tight">
                    <span className="text-sm font-semibold sm:text-base">
                        {label}
                    </span>
                    {showPhone && (
                        <span className="mt-0.5 inline-flex items-center gap-1.5 text-xs font-medium text-white/90 sm:text-sm">
                            <i
                                className="fas fa-phone text-[0.65rem] opacity-80"
                                aria-hidden="true"
                            />
                            <span className="tabular-nums">
                                {WHATSAPP_DISPLAY}
                            </span>
                        </span>
                    )}
                </span>
                <i
                    className="fas fa-arrow-up-right-from-square ml-1 text-xs opacity-70 transition group-hover:translate-x-0.5 group-hover:opacity-100"
                    aria-hidden="true"
                />
            </>
        ));

    return (
        <a
            href={WHATSAPP_URL}
            target="_blank"
            rel="noopener noreferrer"
            className={`${baseLinkClass} ${
                variant === "compact" ? "px-4 py-2 text-sm" : "px-4 py-3"
            } ${className}`}
            aria-label={`WhatsApp ${WHATSAPP_DISPLAY}`}
            {...props}
        >
            {content}
        </a>
    );
}

export default function WhatsAppButton() {
    return (
        <a
            href={WHATSAPP_URL}
            target="_blank"
            rel="noopener noreferrer"
            aria-label={`Escribinos por WhatsApp al ${WHATSAPP_DISPLAY}`}
            className="group fixed bottom-4 right-4 z-50 inline-flex items-center gap-3 rounded-md bg-[#25d366] py-3 pl-3 pr-4 text-white shadow-lg shadow-black/35 transition duration-300 hover:-translate-y-0.5 hover:bg-[#1ebe57] hover:shadow-[#25d366]/35 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#25d366]/70 sm:bottom-6 sm:right-6"
        >
            <span className="relative flex h-11 w-11 items-center justify-center rounded-md bg-white/15 text-2xl ring-2 ring-white/20 transition group-hover:bg-white/25">
                <i className="fab fa-whatsapp" aria-hidden="true" />
            </span>
            <span className="hidden flex-col items-start leading-tight sm:flex">
                <span className="text-sm font-semibold">WhatsApp</span>
                <span className="text-xs font-medium tabular-nums text-white/90">
                    {WHATSAPP_DISPLAY}
                </span>
            </span>
        </a>
    );
}

export { WHATSAPP_URL, WHATSAPP_DISPLAY, WHATSAPP_PHONE };
