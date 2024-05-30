import React from 'react';
import Main from '../shared/Main'
import ContactForm from '../shared/ContactForm'
import Process from '../shared/Process'
import {LanguageContext} from '../LanguagesContext';
import Particles from 'react-particles-js';


function Contact(props){
  return (
        <div className="main_contact container"  style={{position: 'absolute', top: '0'}}>
          <div className="row">
            <div className="col-md-6 col-sm-12  text-white" >
              <p className="first_title">
                <LanguageContext.Consumer>
                    {({contact}) => {
                      return <div>{contact.solutions}</div>
                    }}
                </LanguageContext.Consumer>
              </p>
              <p className="second_title">
                <LanguageContext.Consumer>
                    {({contact}) => {
                      return <div>{contact.integral}</div>
                    }}
                </LanguageContext.Consumer>  
              </p> <br/>
            </div>
            <div className="col-md-6 col-sm-12  mt-2">
              <div >
                <ContactForm  token={props.token} />
              </div>
            </div>
          </div>
          <a href="https://wa.me/543425287592?text=Hola,%20Me%20gustaría%20cotizar%20mi%20proyecto%20" className="whatsapp pt-2 " target="_blank"> <i class="fa fa-whatsapp whatsapp-icon"></i> ✆</a>
        </div>
    )
}

export default Contact;
