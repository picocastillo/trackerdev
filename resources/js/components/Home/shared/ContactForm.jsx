import React from "react";
import { LanguageContext } from "../LanguagesContext";

const fieldClass =
    "form-input border-white/20 bg-white/10 text-white placeholder:text-white/50 focus:border-primary-light focus:ring-primary/40";

const ContactForm = (props) => (
    <form action="/contact-form" method="POST" className="space-y-3">
        <input type="hidden" name="_token" value={props.token} />

        <div className="grid grid-cols-1 gap-3 sm:grid-cols-12">
            <div className="sm:col-span-8">
                <LanguageContext.Consumer>
                    {({ contact }) => (
                        <input
                            type="email"
                            className={fieldClass}
                            name="email"
                            placeholder={contact.email}
                            required
                        />
                    )}
                </LanguageContext.Consumer>
            </div>
            <div className="sm:col-span-4">
                <LanguageContext.Consumer>
                    {({ contact }) => (
                        <input
                            type="text"
                            className={fieldClass}
                            name="name"
                            placeholder={contact.name}
                            required
                        />
                    )}
                </LanguageContext.Consumer>
            </div>
        </div>

        <LanguageContext.Consumer>
            {({ contact }) => (
                <textarea
                    className={fieldClass}
                    name="message"
                    placeholder={contact.message}
                    rows="3"
                    required
                />
            )}
        </LanguageContext.Consumer>

        <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
            <div
                id="g-recaptcha-response"
                className="g-recaptcha"
                data-theme="dark"
                data-sitekey="6LcZvbwZAAAAAGlv3lU91lBCqXHd-2c6gOQX4gjg"
            />
            <LanguageContext.Consumer>
                {({ contact }) => (
                    <input
                        type="submit"
                        className="btn-primary cursor-pointer"
                        value={contact.submit}
                    />
                )}
            </LanguageContext.Consumer>
        </div>
    </form>
);

export default ContactForm;

if (typeof window !== "undefined") {
    window.addEventListener("load", () => {
        const recaptcha = document.querySelector("#g-recaptcha-response");
        if (recaptcha) {
            recaptcha.setAttribute("required", "required");
        }
    });
}
