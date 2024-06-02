import React from 'react'
import ContactForm from '../shared/ContactForm';


const Footer = () =>{
  return (
    <section id="footer" className="mt-2">
      <div className="container">
          <div className="row">
            <div className="col-12 col-sm-6  p-4">
            <h5 className="text-center text-white h3"><span style={{color: 'white'}}>——</span> Contacto<span style={{color: 'white'}}>——</span></h5>
            <div class="row box_social">
              <div className="col-sm-3 col-3">
                <a href="https://www.facebook.com/trackerdev">
                  <i class="fab fa-facebook fa-3x facebook pr-2"></i>
                </a>
              </div>
              <div className="col-sm-3 col-3">
                <a href="www.linkedin.com/in/trackerdev-solutions">
                  <i class="fab fa-linkedin-in fa-3x pr-2 linkedin"></i>
                </a>
              </div>
              <div className="col-sm-3 col-3">
                <a href="https://www.instagram.com/trackerdev/">
                  <i class="fab fa-instagram fa-3x instagram  pr-2"></i>
                </a>
              </div>
              <div className="col-sm-3 col-3">
                <a href="https://wa.me/543425287592?text=Hola,%20Me%20gustaría%20cotizar%20mi%20proyecto%20">
                  <i class="fab fa-whatsapp fa-3x twitter  pr-2"></i>
                </a>
              </div>
            </div>
            </div>
            <div className="col-12 col-sm-6 ">
              <div className="box_form">
                <ContactForm />
              </div>
            </div>


            
          </div>
          <div className="row">
            <div className="col-12">
              <hr 
              style={{
                color: 'gray',
                height: 5
            }}
              />
            </div>
            <div className="col-12">
              <div class=" text-center ">
                <span>©</span>
                    <a href="https://trackerdev.com.ar" target="_black">
                      <img  style={{marginLeft: '1px',  marginTop: '5px'}}  height="30" src="/images/td_black.png" alt="Trackerdev"></img>
                    </a>
                <span> TODOS LOS DERECHOS RESERVADOS </span>
              </div>
            </div>
          </div>
      </div>
    </section>
  )
}

export default Footer;
