import React,{useEffect,useState} from 'react';
import Main from '../shared/Main'
import Process from '../shared/Process'
import Image from '../assets/logo.png'
import {LanguageContext} from '../LanguagesContext';
import Typist from 'react-typist';
import Methodology from './Methodology';
import Projects from './Projects';
import ParticleComponent from '../shared/ParticleComponent';
import Footer from '../shared/Footer';




function Home (){
      const [count,setCount] = useState("0")
      const number = 3
      useEffect( () => {
        let start =0;
        const end = 1000;
        if (start===end) return;

        let mil = 1;
        let incrementTime = (mil / end) * 1000;


        let timer = setInterval( () => {
          start+=1;
          setCount(String(start)+1)
          if (start==end) clearInterval(timer)
        },incrementTime);

      },[number])

      return (
        <Main>
            <ParticleComponent />
            <div >
              <div id="section_1">
                <div className="vertical_center">
                  <div className="hero_1" >
                    <div className="container-fluid">
                      <div className="row " >
                          <div data-aos="fade-right" className="col-sm-6 col-12 box_text" >
                              <LanguageContext.Consumer>
                                    {({home}) => {
                                      return (
                                        <Typist>
                                          <span className="first_text_main mt-2 text-center ml-2"  > {home._1} </span>
                                          <br />
                                          <div className="container">
                                            <p className="text_main mt-3 ml-2" > ✓ {home._2} </p>
                                            <p className="text_main mt-3 ml-2" >  ✓ {home._3} </p>
                                            <p className="text_main mt-3 ml-2" > ✓ {home._4} </p>
                                          </div>
                                        </Typist>
                                      )
                                    }}
                              </LanguageContext.Consumer> 
                          </div >
                          <div className="col-sm-6 col-12" >
                              <div className="container">
                                <img className="my_img" data-aos="flip-right" data-aos-delay="1000" height="150" src="/images/td_white.png"></img>
                                <div 
                                data-aos="fade-zoom-in"
                                data-aos-easing="ease-in-back"
                                data-aos-delay="3000"
                                data-aos-offset="0"
                                className="right_text_main">
                                  <Typist cursor={{show: false}}>
                                    <Typist.Delay ms={3000} />
                                      <p className="text_right">Somos un equipo de trabajo dedicado al planeamiento, desarrollo y pruebas de <i>Software Web y Mobile.</i></p>
                                  </Typist>
                                </div>
                              </div>
                          </div >
                      </div>
                      <div className="row">
                        <div className="col-12">
                          {/* <a href="#section_2" className="my_seemore" >Ver Mas</a>
                          <i class="fas fa-chevron-down my_seemore_icon"></i> */}
                          <div 
                            data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="3000" data-aos-offset="0">
                            <a href="#section_2" className="my_seemore d-none d-sm-block" >Ver Mas</a>
                            <a href="#section_2"><span ></span><section id="see_more_icon" class="demo"> </section></a>
                          </div>

                          

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
              <br/>
              <br/>
              <br/>
              <br/>
              <br/>
            <section id="section_2">
              <div className="container">
                <span data-aos="zoom-out" className="big_title pt-3">¿Qué hacemos?</span>
                <div className="row">
                  <div data-aos="zoom-in"  class="col-sm-3 col-12 animate__animated animate__zoomInUp">
                      <img src="/images/comercio-electronico.svg"  style={{border: 0, margin: '10% auto', display: 'block'}}  height="170"  class="img-thumbnail"></img>
                      <p class=" title font-weight-bold pb-2 "  >Sistemas Web</p>
                      <span className="font-italic text">Desarrollamos sistemas informáticos a medidas ya sea para tener un control total de tu negocio, tener un tablero de comandos y modificar información propia de tu negocio o llevar a cabo una idea que tenes en mente, ajustando un plan de negocio adecuado, un pla estratégico, etc</span>
                  </div>
                  <div data-aos="zoom-in"  class="col-sm-3 col-12 animate__animated animate__zoomInUp">
                      <img src="/images/paleta-de-pintura.svg"  style={{border: 0, margin: '10% auto', display: 'block'}}  height="170"  class="img-thumbnail"></img>
                      <p class=" title font-weight-bold pb-2"  >Diseño UX y UI</p>
                      <span className="font-italic text">Ayudamos a materializar la idea que tenes en mente, para esto contamos con profesionales capacitados y lo suficientemente experimentados como para realizar un prototipo de manera que se pueda navergar a travez de la aplicación, ya sea Móvil o Web, definiendo también el concepto estético de la marca.</span>

                  </div>
                  <div data-aos="zoom-in"  class="col-sm-3 col-12 animate__animated animate__zoomInUp">
                      <img src="/images/app.svg"  style={{border: 0, margin: '10% auto', display: 'block'}} height="170"  class="img-thumbnail"></img>
                      <p class="title font-weight-bold pb-2"  >Aplicaciones Móviles</p>
                      <span className="font-italic text">
                        Desarrollamos aplicaciones móviles las cuales pueden ejecutarse tanto en
                         el sistema operativo Android como IOS. Estas aplicaciones en general
                          se conectan con un sistema Web el cual, no solo lo alimentan sino que también modifican información centralizada,
                          la misma que puede ser monitoriada desde un sistema que se accede mediante un explorador. </span>

                  </div>
                  <div data-aos="zoom-in" class="col-sm-3 col-12 animate__animated animate__zoomInUp">
                      <img style={{border: 0, margin: '10% auto', display: 'block'}} src="/images/admin.svg"  height="170"  class="img-thumbnail"></img>
                      <p class="title font-weight-bold pb-2"  >Landing Page</p>
                      <span className="font-italic text">
                        Desarrolamos tu Landing Page, la cual tiene como objetivo 
                        convertir usuarios en clientes finales. Esta misma es una pagina de
                         "aterrizage" donde se quiere capturar un potencial cliente. Es mediante esta
                      que se puede conseguir la oportunidad perfecta para contactar directamente y de
                       forma personalizada con los clientes para asi poder influir en su desicion de compra</span>
                  </div>
                  
                </div>
              </div>
            </section>


            
            <Methodology />
            <Projects />

           


            <div className="container">
              <div data-aos="zoom-in-up" className="row my-2">
                <div  class="embed-responsive embed-responsive-16by9">
                  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/vTcMYK8VvIo?rel=0" allowfullscreen></iframe>
                </div>
              </div>
            </div>


          <Footer />  
            

          
        </Main>
    )
}

export default (Home);
