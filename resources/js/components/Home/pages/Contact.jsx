import React from "react";
import { LanguageContext } from "../LanguagesContext";
import ParticleComponent from "../shared/ParticleComponent";
import { WHATSAPP_URL, WhatsAppLink } from "../shared/WhatsAppButton";

function Contact() {
    return (
        <section className="relative flex min-h-screen items-center overflow-hidden py-28">
            <ParticleComponent />
            <div className="relative z-10 mx-auto w-full max-w-3xl px-4 text-center md:px-6">
                <div className="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-md bg-[#25d366]/15 text-[#25d366]">
                    <i
                        className="fab fa-whatsapp text-4xl"
                        aria-hidden="true"
                    />
                </div>
                <p className="font-display text-sm font-medium uppercase tracking-[0.25em] text-primary-light">
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
                <WhatsAppLink className="mt-8 inline-flex items-center gap-2 rounded-md bg-[#25d366] px-5 py-3 text-base font-semibold text-white transition hover:bg-[#1ebe57]">
                    <span className="tabular-nums">+54 342 528-7592</span>
                </WhatsAppLink>
                <p className="mt-4 text-sm text-white/50">
                    <a
                        href={WHATSAPP_URL}
                        target="_blank"
                        rel="noreferrer"
                        className="underline-offset-2 hover:underline"
                    >
                        Abrir chat de WhatsApp
                    </a>
                </p>
            </div>
        </section>
    );
}

export default Contact;
