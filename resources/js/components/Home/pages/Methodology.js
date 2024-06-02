import React, {useState, useEffect} from 'react';
import Main from '../shared/Main'
import {LanguageContext} from '../LanguagesContext';
import Particles from 'react-particles-js';


const items = ['my_circle_0', 'my_circle_1', 'my_circle_2', 'my_circle_3']

const steps = [
  {
    id: 0,
    
  },
  {
    id: 1,
    
  },
  {
    id: 2,
   
  },
  {
    id: 3,
    
  },
]


function Methodology (){
  const [index, set] = useState(0)
  
  return (
    <section id="section_3">
      <div className="container">
        <div className="row">
          <div className="container_title">
            <h2><span className="big_title"  data-aos="zoom-out" >Avanzamos juntos en el desarrollo</span></h2>
          </div>
        </div>

        <div className="row mt-4">
          <div className="col-12 col-sm-6 ">
            <div data-aos="fade-down-right" data-aos-delay="300">
              <div className="row">
                <div className="col-sm-4 col-12">
                  <img src="/images/method_1.svg" style={{border: 0}}  width="200" height="400"  class="img-thumbnail"></img></div>
                <div className="col-sm-8 col-12">
                  <h5>1°<span style={{color: 'red'}}>——</span> <b className="title">¿Cómo inicia un proyecto?</b></h5>
                  <span className="pl-4 text">
                  Se realiza la captura de requerimientos, esto es
                  saber qué se espera del sistema, qué solución
                  cumple con mis expectativas, cómo me imagino el
                  sistema. Resultado de esta etapa se desprende un
                  documento contenedor de objetivos principales.
                  Aquí se parte de una idea inicial con uno o varios
                  problemas a solucionar. El objetivo en esta etapa es
                  llevar nuestra idea a un producto realizable con
                  objetivos claros, también conocido como MVP.
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div className="col-12 col-sm-6 ">
            <div data-aos="fade-down-left" data-aos-delay="600">
              <div className="row">
                <div className="col-sm-4 col-12">
                  <img src="/images/method_2.svg" style={{border: 0}}  width="200" height="400"  class="img-thumbnail"></img></div>
                <div className="col-sm-8 col-12">
                  <h5>2°<span style={{color: 'red'}}>——</span> <b className="title">Se realiza prototipado</b></h5>
                  <span className="pl-4 text">
                  Se realiza el prototipado de una primer solución,
                  cumpliendo con los objetivos principales y teniendo
                  una primera versión del producto. Como resultado de
                  esta etapa se obtiene un prototipo. La intención de esta
                  etapa es encontrar la funcionalidad básica de manera
                  de alcanzar una primera versión que nos posibilite en
                  un lapso de tiempo corto un retorno de la inversión. En
                  este momento ya sabremos cómo se navega a través
                  de cada pantalla, funcionalidades de cada botón,
                  concepto, etc.
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div className="row my-3">
            <div className="col-12 col-sm-6 ">
              <div data-aos="fade-down-right" data-aos-delay="900">
                <div className="row">
                  <div className="col-sm-4 col-12">
                    <img src="/images/method_3.svg" style={{border: 0}}  width="200" height="400"  class="img-thumbnail"></img></div>
                  <div className="col-sm-8 col-12">
                    <h5>3°<span style={{color: 'red'}}>——</span> <b className="title">Principio de acuerdo</b></h5>
                    <span className="pl-4 text">
                    Si se encuentra conformidad con el producto resultado
                    de la etapa anterior se reliza un presupuesto, se genera
                    una estimación en horas para obtener un producto
                    funcional del prototipado de la segunda etapa, un
                    producto alcanzable. Para esto se hacen diseños de la
                    arquitectura funcional obteniendo como resultado un
                    documento en donde se detalla el desglose de tareas
                    necesarias para tener un producto final, con cada tarea
                    estimada en horas necesarias para completarla.
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div className="col-12 col-sm-6 ">
              <div data-aos="fade-down-left" data-aos-delay="1200">
                <div className="row">
                  <div className="col-sm-4 col-12">
                    <img src="/images/method_4.svg" style={{border: 0}}  width="200" height="400"  class="img-thumbnail"></img></div>
                  <div className="col-sm-8 col-12">
                    <h5>4°<span style={{color: 'red'}}>——</span> <b className="title">Producto funcional</b></h5>
                    <span className="pl-4 text">
                    Si se aprueba el presupuesto de una aplicación inicial
                    se lleva a cabo el desarrollo y pruebas necesarias para
                    un primer producto final. En la etapa de desarrollo y
                    pruebas, se puede tener un seguimiento constante del
                    proyecto, qué etapa del plan se esta ejecutando, qué
                    nuevo avances hay, si se cumplen con las
                    estimaciones, etc. Finalmente tendremos nuestra
                    primer versión del producto completamente
                    funcionando.
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
                  
      </div>
   </section>
  )


}

export default Methodology;
