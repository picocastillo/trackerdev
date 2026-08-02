import React from "react";
import ContactForm from "./ContactForm";
import { WHATSAPP_URL, WhatsAppLink } from "./WhatsAppButton";

const socialLinks = [
    {
        href: "https://www.facebook.com/trackerdev",
        icon: "fab fa-facebook-f",
        label: "Facebook",
        className: "hover:text-[#3b5998]",
    },
    {
        href: "https://www.linkedin.com/in/trackerdev-solutions",
        icon: "fab fa-linkedin-in",
        label: "LinkedIn",
        className: "hover:text-[#0e76a8]",
    },
    {
        href: "https://www.instagram.com/trackerdev/",
        icon: "fab fa-instagram",
        label: "Instagram",
        className: "hover:text-[#fd1d1d]",
    },
    {
        href: WHATSAPP_URL,
        icon: "fab fa-whatsapp",
        label: "WhatsApp",
        className: "hover:text-[#25d366]",
    },
];

const Footer = ({ token }) => (
    <section
        id="contacto"
        className="relative mt-0 border-t border-white/10 bg-gradient-to-b from-brand-dark via-[#120f0f] to-black"
    >
        <div className="mx-auto max-w-7xl px-4 py-16 md:px-6 md:py-20">
            <div className="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-16">
                <div>
                    <h2 className="font-display text-3xl font-bold text-white md:text-4xl">
                        Contacto
                    </h2>
                    <p className="mt-3 max-w-md text-white/70">
                        Contanos tu idea y te ayudamos a convertirla en un
                        producto web o móvil a medida.
                    </p>
                    <WhatsAppLink className="mt-5 inline-flex items-center gap-2 text-base font-semibold text-[#25d366] transition hover:text-[#1ebe57]">
                        <span className="tabular-nums">+54 342 528-7592</span>
                    </WhatsAppLink>
                    <div className="mt-8 flex flex-wrap gap-4">
                        {socialLinks.map((link) => (
                            <a
                                key={link.label}
                                href={link.href}
                                target="_blank"
                                rel="noreferrer"
                                aria-label={link.label}
                                className={`inline-flex h-12 w-12 items-center justify-center rounded-md border border-white/15 text-white/80 transition ${link.className}`}
                            >
                                <i
                                    className={`${link.icon} text-xl`}
                                    aria-hidden="true"
                                />
                            </a>
                        ))}
                    </div>
                </div>
                <div>
                    <ContactForm token={token} />
                </div>
            </div>

            <div className="mt-14 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 text-center text-sm text-white/60 sm:flex-row sm:text-left">
                <a
                    href="https://trackerdev.com.ar"
                    target="_blank"
                    rel="noreferrer"
                    className="inline-flex items-center gap-2 text-white/80 hover:text-white"
                >
                    <img
                        height="28"
                        src="/images/td_white.png"
                        alt="TrackerDev"
                        className="h-7 w-auto"
                    />
                    <span className="font-display font-semibold">
                        TrackerDev
                    </span>
                </a>
                <p>
                    © {new Date().getFullYear()} Todos los derechos reservados
                </p>
            </div>
        </div>
    </section>
);

export default Footer;
