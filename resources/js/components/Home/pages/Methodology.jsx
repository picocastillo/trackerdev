import React from "react";
import { LanguageContext } from "../LanguagesContext";

const stepIcons = [
    "fas fa-lightbulb",
    "fas fa-drafting-compass",
    "fas fa-file-signature",
    "fas fa-rocket",
];

function Methodology() {
    return (
        <LanguageContext.Consumer>
            {({ methodology }) => (
                <section
                    id="metodologia"
                    className="relative bg-gradient-to-b from-brand-dark via-stone-950 to-brand-dark py-20 md:py-28"
                >
                    <div className="mx-auto max-w-7xl px-4 md:px-6">
                        <div className="mx-auto max-w-3xl text-center">
                            <h2
                                data-aos="fade-up"
                                data-aos-duration="900"
                                className="font-display text-3xl font-bold text-white md:text-4xl"
                            >
                                {methodology.left_1}
                            </h2>
                            <p
                                data-aos="fade-up"
                                data-aos-delay="120"
                                className="mt-3 text-white/65"
                            >
                                {methodology.left_2}
                            </p>
                            <div
                                data-aos="zoom-in"
                                data-aos-delay="200"
                                className="mx-auto mt-6 h-px w-24 origin-center bg-primary"
                            />
                        </div>

                        <div className="mt-16 grid grid-cols-1 gap-10 md:grid-cols-2 md:gap-12">
                            {methodology.right_1.map((step, index) => (
                                <article
                                    key={step.id}
                                    data-aos={
                                        index % 2 === 0
                                            ? "fade-right"
                                            : "fade-left"
                                    }
                                    data-aos-delay={80 + index * 100}
                                    className="marketing-service flex gap-5"
                                >
                                    <div className="relative shrink-0">
                                        <div className="marketing-service-icon flex h-14 w-14 items-center justify-center rounded-md bg-primary/25 text-primary-light ring-1 ring-primary/40 sm:h-16 sm:w-16">
                                            <i
                                                className={`${stepIcons[index]} text-xl sm:text-2xl`}
                                                aria-hidden="true"
                                            />
                                        </div>
                                        <span className="absolute -right-1 -top-1 flex h-6 w-6 items-center justify-center rounded-full bg-primary font-display text-xs font-bold text-white">
                                            {index + 1}
                                        </span>
                                    </div>
                                    <div className="min-w-0 pt-0.5">
                                        <p className="font-display text-sm font-semibold uppercase tracking-wider text-primary-light">
                                            Paso {index + 1}
                                        </p>
                                        <h3 className="mt-1 font-display text-xl font-semibold text-white">
                                            {step.title}
                                        </h3>
                                        <p className="mt-3 text-sm leading-relaxed text-white/65">
                                            {step.content}
                                        </p>
                                    </div>
                                </article>
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </LanguageContext.Consumer>
    );
}

export default Methodology;
