import React from 'react';
import Contact from '../pages/Contact';
import {LanguageContext} from '../LanguagesContext';


const ContactForm = (props) => {
    const [name,setName] = React.useState('');
    const [msg,setMsg] = React.useState('');
    const [mail,setMail] = React.useState('');
    return (
            <form action="/contact-form" method="POST">
                <input type="hidden" name="_token" value={props.token} ></input>
                
                <div className="form-row">
                    <div className="col-8">
                        <LanguageContext.Consumer>
                            {({contact}) => {
                                return <input style={styles.my_input}  type="email" className="form-control col" name="email"   placeholder={contact.email} required></input>
                            }}
                        </LanguageContext.Consumer>
                    </div>
                    <div className="col-4">
                        <LanguageContext.Consumer>
                            {({contact}) => {
                                return <input style={styles.my_input}  type="text" className="form-control col " name="name"  placeholder={contact.name} required></input>
                            }}
                        </LanguageContext.Consumer>
                    </div>
                </div>
                <div className="form-row mt-2">
                    <div className="col">
                        <LanguageContext.Consumer>
                            {({contact}) => {
                                return <textarea  style={styles.my_input} className="form-control col" name="message" placeholder={contact.message} rows="3" required></textarea>
                            }}
                        </LanguageContext.Consumer>
                        
                    </div>
                </div>
                <div className="form-row mt-4">
                    <div className="col-4">
                    <div id="g-recaptcha-response" className="g-recaptcha" data-theme="dark" data-sitekey="6LcZvbwZAAAAAGlv3lU91lBCqXHd-2c6gOQX4gjg"></div>
                    </div>
                    <div className="col-2 offset-5">
                    <LanguageContext.Consumer>
                        {({contact}) => {
                            return <input type="submit" className="contact_bottom" value={contact.submit}></input>
                        }}
                    </LanguageContext.Consumer>
                    
                   
                    </div>
                </div>
                
                
            </form>
    )
}

export default ContactForm;

const styles ={
   my_input: {
       backgroundColor: 'rgba(0,0,0,0.2)',
       color: 'white',
    }
}

window.onload = function() {
    var $recaptcha = document.querySelector('#g-recaptcha-response');

    if($recaptcha) {
        $recaptcha.setAttribute("required", "required");
    }
};