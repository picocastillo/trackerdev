import React from "react";
import Main from "../shared/Main";
import { LanguageContext } from "../LanguagesContext";
import Methodology from "./Methodology";
import Projects from "./Projects";
import ParticleComponent from "../shared/ParticleComponent";
import Footer from "../shared/Footer";
import { WhatsAppLink } from "../shared/WhatsAppButton";

const services = [
    {
        title: "Sistemas Web",
        image: "/images/comercio-electronico.svg",
        description:
            "Sistemas a medida para controlar tu negocio, tableros de comando e ideas convertidas en productos con un plan estratégico claro.",
    },
    {
        title: "Diseño UX y UI",
        image: "/images/paleta-de-pintura.svg",
        description:
            "Materializamos tu idea con prototipos navegables para web o móvil, definiendo también el concepto estético de la marca.",
    },
    {
        title: "Aplicaciones Móviles",
        image: "/images/app.svg",
        description:
            "Apps para Android e iOS conectadas a un sistema web centralizado, con monitoreo y operación desde el navegador.",
    },
    {
        title: "Landing Page",
        image: "/images/admin.svg",
        description:
            "Páginas de aterrizaje pensadas para convertir visitantes en clientes y abrir una conversación personalizada.",
    },
];

function Home({ token }) {
    return (
        <Main>
            <section className="relative flex min-h-screen items-center overflow-hidden">
                <ParticleComponent />

                <div className="relative z-10 mx-auto w-full max-w-7xl px-4 pb-20 pt-28 md:px-6 md:pb-24 md:pt-32">
                    <div className="max-w-3xl">
                        <img
                            src="/images/td_white.png"
                            alt="TrackerDev"
                            className="marketing-fade-up h-14 w-auto md:h-20"
                        />
                        <p className="marketing-fade-up marketing-delay-1 mt-4 font-display text-sm font-medium uppercase tracking-[0.25em] text-primary-light md:text-base">
                            TrackerDev
                        </p>
                        <LanguageContext.Consumer>
                            {({ home }) => (
                                <h1 className="marketing-fade-up marketing-delay-2 mt-4 font-display text-4xl font-bold leading-tight text-white md:text-6xl">
                                    {home._1}{" "}
                                    <span className="text-primary-light">
                                        {home._2.replace(/\.$/, "")}
                                    </span>
                                </h1>
                            )}
                        </LanguageContext.Consumer>
                        <p className="marketing-fade-up marketing-delay-3 mt-5 max-w-xl text-base text-white/75 md:text-lg">
                            Equipo en Santa Fe Capital dedicado al planeamiento,
                            desarrollo y pruebas de software web y mobile.
                        </p>
                        <div className="marketing-fade-up marketing-delay-4 mt-8 flex flex-wrap gap-3">
                            <a href="#contacto" className="btn-primary">
                                Contactar
                            </a>
                            <WhatsAppLink className="inline-flex items-center justify-center gap-2 rounded-md bg-[#25d366] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1ebe57]" />
                            <a
                                href="#proyectos"
                                className="inline-flex items-center justify-center rounded-md border border-white/30 px-4 py-2 text-sm font-semibold text-white transition hover:border-white hover:bg-white/10"
                            >
                                Ver trabajos
                            </a>
                        </div>
                    </div>
                </div>

                <a
                    href="#servicios"
                    className="marketing-scroll-cue absolute bottom-8 left-1/2 z-10 -translate-x-1/2 text-white/70 transition hover:text-white"
                    aria-label="Ver más"
                >
                    <span className="mb-2 block text-center font-display text-xs uppercase tracking-widest">
                        Ver más
                    </span>
                    <i
                        className="fas fa-chevron-down text-xl"
                        aria-hidden="true"
                    />
                </a>
            </section>

            <section
                id="servicios"
                className="relative bg-gradient-to-b from-brand-dark via-[#1f1515] to-brand-dark py-20 md:py-28"
            >
                <div className="mx-auto max-w-7xl px-4 md:px-6">
                    <h2
                        data-aos="fade-up"
                        className="font-display text-center text-3xl font-bold text-white md:text-4xl"
                    >
                        ¿Qué hacemos?
                    </h2>
                    <p
                        data-aos="fade-up"
                        data-aos-delay="100"
                        className="mx-auto mt-3 max-w-2xl text-center text-white/65"
                    >
                        Soluciones integrales para llevar software a tu negocio.
                    </p>

                    <div className="mt-14 grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
                        {services.map((service, index) => (
                            <article
                                key={service.title}
                                data-aos="fade-up"
                                data-aos-delay={100 + index * 80}
                                className="text-center"
                            >
                                <img
                                    src={service.image}
                                    alt={service.title}
                                    className="mx-auto h-28 w-auto object-contain"
                                />
                                <h3 className="mt-5 font-display text-xl font-semibold text-white">
                                    {service.title}
                                </h3>
                                <p className="mt-3 text-sm leading-relaxed text-white/65">
                                    {service.description}
                                </p>
                            </article>
                        ))}
                    </div>
                </div>
            </section>

            <Methodology />
            <Projects />

            <section className="bg-brand-dark py-16 md:py-20">
                <div className="mx-auto max-w-5xl px-4 md:px-6">
                    <div
                        data-aos="fade-up"
                        className="relative aspect-video w-full overflow-hidden rounded-lg border border-white/10"
                    >
                        <iframe
                            className="absolute inset-0 h-full w-full"
                            src="https://www.youtube.com/embed/vTcMYK8VvIo?rel=0"
                            title="Trackerdev video"
                            allowFullScreen
                        />
                    </div>
                </div>
            </section>

            <Footer token={token} />
        </Main>
    );
}

export default Home;
