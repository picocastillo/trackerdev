import React, { useState } from "react";

const slides = [
    {
        id: 0,
        title: "Comprobar",
        badges: [
            "React Native",
            "Firebase",
            "Redux",
            "Thunk",
            "Bootstrap",
            "XD",
        ],
        description:
            "Aplicación para personas con diabetes: cuestionarios, seguimiento y recolección de datos útiles para el tratamiento.",
        image: "/images/proj_1.png",
    },
    {
        id: 1,
        title: "Show Travelers",
        badges: [
            "React Native",
            "PHP",
            "Bootstrap",
            "Redux",
            "Google Map",
            "Sagas",
        ],
        description:
            "App móvil para el viajero con alertas relevantes y oferta de visitas turísticas durante el viaje.",
        image: "/images/proj_2.png",
    },
    {
        id: 2,
        title: "Sprint",
        badges: [
            "React Native",
            "Laravel",
            "Barcode",
            "React JS",
            "Bootstrap",
            "Redux",
            "Sagas",
            "Expo",
        ],
        description:
            "Sistema web y dos apps móviles (iOS y Android) para logística de paquetería.",
        image: "/images/proj_4_2.png",
        secondaryImage: "/images/proj_4_1.png",
    },
    {
        id: 3,
        title: "Prego",
        badges: [
            "React Native",
            "Laravel",
            "React JS",
            "Bootstrap",
            "Redux",
            "Sagas",
            "Landing Page",
            "Expo",
            "Figma",
        ],
        description:
            "Plataforma que conecta la oferta de profesionales con la demanda de trabajos.",
        image: "/images/proj_5_2.png",
        secondaryImage: "/images/proj_5_1.png",
    },
    {
        id: 4,
        title: "Moveler",
        badges: [
            "React Native",
            "Laravel",
            "React JS",
            "Redux",
            "Bootstrap",
            "Sagas",
            "Expo",
            "Figma",
        ],
        description:
            "CMS web que alimenta una aplicación móvil con contenidos y gestión centralizada.",
        image: "/images/proj_6_2.png",
        secondaryImage: "/images/proj_6_1.png",
    },
    {
        id: 5,
        title: "Estoker",
        badges: ["Laravel", "React JS", "Bootstrap", "Figma"],
        description:
            "Gestión de productos por Excel o interfaz gráfica, con control de stock y operación diaria.",
        image: "/images/proj_7.png",
    },
    {
        id: 6,
        title: "Seccoplac",
        badges: ["Laravel", "React JS", "Bootstrap", "Landing Page"],
        description:
            "Sitio web para captar clientes y franquiciados, mostrar productos y atender con chat bot integrado.",
        image: "/images/proj_3.png",
    },
];

function BadgeList({ badges }) {
    return (
        <div className="mt-3 flex flex-wrap gap-2">
            {badges.map((badge) => (
                <span
                    key={badge}
                    className="inline-flex border border-white/15 bg-white/5 px-2.5 py-0.5 text-xs font-medium text-white/75"
                >
                    {badge}
                </span>
            ))}
        </div>
    );
}

export default function Projects() {
    const [activeIndex, setActiveIndex] = useState(0);
    const slide = slides[activeIndex];

    const goPrev = () =>
        setActiveIndex((i) => (i === 0 ? slides.length - 1 : i - 1));
    const goNext = () =>
        setActiveIndex((i) => (i === slides.length - 1 ? 0 : i + 1));

    return (
        <section
            id="proyectos"
            className="relative bg-gradient-to-br from-primary-dark via-brand-dark to-stone-900 py-20 md:py-28"
        >
            <div className="mx-auto max-w-7xl px-4 md:px-6">
                <h2
                    data-aos="fade-up"
                    className="font-display text-center text-3xl font-bold text-white md:text-4xl"
                >
                    Algunos de nuestros trabajos
                </h2>

                <div className="relative mt-12">
                    <ol className="mb-8 flex list-none justify-center gap-2 p-0">
                        {slides.map((s, i) => (
                            <li key={s.id}>
                                <button
                                    type="button"
                                    aria-label={`Proyecto ${i + 1}`}
                                    onClick={() => setActiveIndex(i)}
                                    className={`h-2.5 w-2.5 rounded-full transition ${
                                        i === activeIndex
                                            ? "bg-primary-light scale-110"
                                            : "bg-white/30 hover:bg-white/50"
                                    }`}
                                />
                            </li>
                        ))}
                    </ol>

                    <div
                        key={slide.id}
                        data-aos="fade-up"
                        className="grid grid-cols-1 items-center gap-8 lg:grid-cols-12 lg:gap-10"
                    >
                        <div className="lg:col-span-5">
                            <h3 className="font-display text-2xl font-bold text-white md:text-3xl">
                                {slide.title}
                            </h3>
                            <BadgeList badges={slide.badges} />
                            <p className="mt-4 text-base leading-relaxed text-white/70">
                                {slide.description}
                            </p>
                        </div>
                        <div className="lg:col-span-7">
                            <div className="grid gap-3">
                                <img
                                    src={slide.image}
                                    alt={slide.title}
                                    className="w-full rounded-lg border border-white/10 object-cover"
                                />
                                {slide.secondaryImage && (
                                    <img
                                        src={slide.secondaryImage}
                                        alt={`${slide.title} detalle`}
                                        className="ml-auto w-2/3 rounded-lg border border-white/10 object-cover"
                                    />
                                )}
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        onClick={goPrev}
                        className="absolute left-0 top-1/2 hidden -translate-y-1/2 rounded-md border border-white/15 bg-black/40 px-3 py-2 text-white transition hover:bg-black/60 md:inline-flex"
                        aria-label="Anterior"
                    >
                        <i className="fas fa-chevron-left" aria-hidden="true" />
                    </button>
                    <button
                        type="button"
                        onClick={goNext}
                        className="absolute right-0 top-1/2 hidden -translate-y-1/2 rounded-md border border-white/15 bg-black/40 px-3 py-2 text-white transition hover:bg-black/60 md:inline-flex"
                        aria-label="Siguiente"
                    >
                        <i
                            className="fas fa-chevron-right"
                            aria-hidden="true"
                        />
                    </button>

                    <div className="mt-8 flex justify-center gap-3 md:hidden">
                        <button
                            type="button"
                            onClick={goPrev}
                            className="rounded-md border border-white/15 bg-black/40 px-4 py-2 text-white"
                            aria-label="Anterior"
                        >
                            <i
                                className="fas fa-chevron-left"
                                aria-hidden="true"
                            />
                        </button>
                        <button
                            type="button"
                            onClick={goNext}
                            className="rounded-md border border-white/15 bg-black/40 px-4 py-2 text-white"
                            aria-label="Siguiente"
                        >
                            <i
                                className="fas fa-chevron-right"
                                aria-hidden="true"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </section>
    );
}
