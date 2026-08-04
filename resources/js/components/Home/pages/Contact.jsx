import React from "react";
import { LanguageContext } from "../LanguagesContext";
import ParticleComponent from "../shared/ParticleComponent";
import { WHATSAPP_DISPLAY, WhatsAppLink } from "../shared/WhatsAppButton";

function Contact() {
    return (
        <section className="relative flex min-h-screen items-center overflow-hidden py-28">
            <ParticleComponent />
            <div className="relative z-10 mx-auto w-full max-w-3xl px-4 text-center md:px-6">
                <div className="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-md bg-[#25d366]/15 text-[#25d366] ring-1 ring-[#25d366]/30">
                    <i
                        className="fab fa-whatsapp text-4xl"
                        aria-hidden="true"
                    />
                </div>
                <p className="inline-flex items-center justify-center gap-2 font-display text-sm font-medium uppercase tracking-[0.25em] text-primary-light">
                    <span aria-hidden="true">👋</span>
                    TrackerDev
                </p>
                <LanguageContext.Consumer>
                    {({ contact }) => (
                        <>
                            <h1 className="mt-3 font-display text-4xl font-bold leading-tight text-white md:text-5xl">
                                {contact.solutions}{" "}
                                <span className="text-primary-light">
                                    {contact.integral}
                                </span>
                            </h1>
                            <p className="mx-auto mt-4 max-w-md text-white/70">
                                {contact.lead}
                            </p>
                        </>
                    )}
                </LanguageContext.Consumer>

                <div className="mx-auto mt-8 flex max-w-md flex-col items-stretch gap-3 sm:items-center">
                    <WhatsAppLink
                        className="w-full justify-center sm:w-auto"
                        label="Abrir chat de WhatsApp"
                    />
                    <p className="inline-flex items-center justify-center gap-2 text-sm text-white/55">
                        <i
                            className="fas fa-phone text-primary-light"
                            aria-hidden="true"
                        />
                        <span className="tabular-nums">{WHATSAPP_DISPLAY}</span>
                    </p>
                    <p className="text-xs text-white/40">
                        Se abre WhatsApp con un mensaje listo para enviar
                    </p>
                </div>
            </div>
        </section>
    );
}

export default Contact;
