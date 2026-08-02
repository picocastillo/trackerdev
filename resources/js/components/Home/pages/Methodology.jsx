import React from "react";

const steps = [
    {
        title: "¿Cómo inicia un proyecto?",
        image: "/images/method_1.svg",
        description:
            "Capturamos requerimientos: qué se espera del sistema, qué solución cumple expectativas y cómo se imagina el producto. El resultado es un documento con objetivos claros — un MVP realizable.",
    },
    {
        title: "Se realiza prototipado",
        image: "/images/method_2.svg",
        description:
            "Prototipamos una primera solución con los objetivos principales. En poco tiempo sabremos cómo se navega cada pantalla, qué hace cada botón y el concepto general del producto.",
    },
    {
        title: "Principio de acuerdo",
        image: "/images/method_3.svg",
        description:
            "Con conformidad sobre el prototipo, armamos presupuesto y estimación en horas. Diseñamos la arquitectura funcional y el desglose de tareas para un producto alcanzable.",
    },
    {
        title: "Producto funcional",
        image: "/images/method_4.svg",
        description:
            "Con el presupuesto aprobado, desarrollamos y probamos la primera versión. Seguimiento constante del plan, avances y estimaciones hasta un producto funcionando.",
    },
];

function Methodology() {
    return (
        <section
            id="metodologia"
            className="relative bg-gradient-to-b from-brand-dark via-stone-950 to-brand-dark py-20 md:py-28"
        >
            <div className="mx-auto max-w-7xl px-4 md:px-6">
                <div className="mx-auto max-w-3xl text-center">
                    <h2
                        data-aos="fade-up"
                        className="font-display text-3xl font-bold text-white md:text-4xl"
                    >
                        Avanzamos juntos en el desarrollo
                    </h2>
                    <p
                        data-aos="fade-up"
                        data-aos-delay="100"
                        className="mt-3 text-white/65"
                    >
                        Partimos de pequeñas ideas que se convierten en grandes
                        proyectos.
                    </p>
                    <div
                        data-aos="fade-up"
                        data-aos-delay="150"
                        className="mx-auto mt-6 h-px w-24 bg-primary"
                    />
                </div>

                <div className="mt-16 grid grid-cols-1 gap-10 md:grid-cols-2 md:gap-12">
                    {steps.map((step, index) => (
                        <article
                            key={step.title}
                            data-aos={
                                index % 2 === 0 ? "fade-right" : "fade-left"
                            }
                            data-aos-delay={100 + index * 80}
                            className="grid grid-cols-1 items-start gap-5 sm:grid-cols-[120px_1fr]"
                        >
                            <img
                                src={step.image}
                                alt={step.title}
                                className="mx-auto h-28 w-auto object-contain sm:mx-0 sm:h-32"
                            />
                            <div>
                                <p className="font-display text-sm font-semibold uppercase tracking-wider text-primary-light">
                                    {index + 1}° paso
                                </p>
                                <h3 className="mt-1 font-display text-xl font-semibold text-white">
                                    {step.title}
                                </h3>
                                <p className="mt-3 text-sm leading-relaxed text-white/65">
                                    {step.description}
                                </p>
                            </div>
                        </article>
                    ))}
                </div>
            </div>
        </section>
    );
}

export default Methodology;
