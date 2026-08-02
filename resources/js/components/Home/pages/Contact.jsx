import React from "react";
import ContactForm from "../shared/ContactForm";
import { LanguageContext } from "../LanguagesContext";
import ParticleComponent from "../shared/ParticleComponent";
import { WHATSAPP_URL } from "../shared/WhatsAppButton";

function Contact(props) {
    return (
        <section className="relative flex min-h-screen items-center overflow-hidden py-28">
            <ParticleComponent />
            <div className="relative z-10 mx-auto w-full max-w-7xl px-4 md:px-6">
                <div className="grid grid-cols-1 items-start gap-12 lg:grid-cols-2 lg:gap-16">
                    <div>
                        <p className="font-display text-sm font-medium uppercase tracking-[0.25em] text-primary-light">
                            TrackerDev
                        </p>
                        <LanguageContext.Consumer>
                            {({ contact }) => (
                                <h1 className="mt-3 font-display text-4xl font-bold leading-tight text-white md:text-5xl">
                                    {contact.solutions}{" "}
                                    <span className="text-primary-light">
                                        {contact.integral}
                                    </span>
                                </h1>
                            )}
                        </LanguageContext.Consumer>
                        <p className="mt-4 max-w-md text-white/70">
                            Escribinos y te respondemos con una propuesta a
                            medida para tu proyecto.
                        </p>
                        <a
                            href={WHATSAPP_URL}
                            className="mt-8 inline-flex items-center gap-2 rounded-md border border-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:border-[#25d366] hover:text-[#25d366]"
                            target="_blank"
                            rel="noreferrer"
                        >
                            <i
                                className="fab fa-whatsapp text-lg"
                                aria-hidden="true"
                            />
                            <span>+54 342 528-7592</span>
                        </a>
                    </div>
                    <div className="rounded-lg border border-white/10 bg-black/30 p-6 backdrop-blur-sm md:p-8">
                        <ContactForm token={props.token} />
                    </div>
                </div>
            </div>
        </section>
    );
}

export default Contact;
