import React, {useState, useEffect} from 'react';



export default function Project(){
    return (
      <section id="section_4" style={{backgroundColor: 'gray'}}>
      <div className="container">
        <h1 data-aos="zoom-out" className="big_title pt-5">Algunos de nuestros trabajos</h1>
        <div id="carouselExampleInterval" className="carousel slide padding_c" data-ride="carousel">
        <ol class="carousel-indicators my-3">
          <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
          <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
          <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
          <li data-target="#carouselExampleCaptions" data-slide-to="3"></li>
          <li data-target="#carouselExampleCaptions" data-slide-to="4"></li>
          <li data-target="#carouselExampleCaptions" data-slide-to="5"></li>
          <li data-target="#carouselExampleCaptions" data-slide-to="6"></li>
          <li data-target="#carouselExampleCaptions" data-slide-to="7"></li>
        </ol>
          <div className="carousel-inner">
            <div  className="carousel-item active" data-interval="2000">
              <div className="row my-4">
                  <div data-aos="zoom-in" className="col-sm-4 col-12 pt-5">
                    <div className="title">Comprobar</div>
                    <span class="badge badge-secondary">React Native</span>&nbsp;
                    <span class="badge badge-secondary">Firebase</span>&nbsp;
                    <span class="badge badge-secondary">Redux</span>&nbsp;
                    <span class="badge badge-secondary">Thunk</span>&nbsp;
                    <span class="badge badge-secondary">Bootstrap</span>&nbsp;
                    <span class="badge badge-secondary">XD</span>&nbsp;
                    <div className="font-italic text">Es una aplicación destinada a personas que padecen de diabétes. Mediante la misma uno puede contestar una serie de preguntas las cuales luego serán útiles para tratar dicha enfermedad y para la recolección de datos. Atravez de la aplicación se obtiene un seguimiento de la enfermedad de cada usuario registrado</div>
                  </div>
                  <div data-aos="zoom-in-up" className="col-sm-8 col-12  ">
                    <img src="/images/proj_1.png" style={{border: 0, backgroundColor: 'gray', }}   height="170"  class="img-thumbnail"></img>
                  </div>
              </div>
            </div>
            <div className="carousel-item" data-interval="2000">
              <div className="row">
                    <div data-aos="zoom-in-up" className="col-sm-8 col-12 box_image">
                      <img src="/images/proj_2.png" style={{border: 0, backgroundColor: 'gray', }}   height="170"  class="img-thumbnail"></img>
                    </div>
                    <div data-aos="zoom-in" className="col-sm-4 col-12 pt-5">
                      <div className="h2">Show Travelers</div>
                    <span class="badge badge-secondary">React Native</span>&nbsp;
                    <span class="badge badge-secondary">PHP</span>&nbsp;
                    <span class="badge badge-secondary">Bootstrap</span>&nbsp;
                    <span class="badge badge-secondary">Redux</span>&nbsp;
                    <span class="badge badge-secondary">Google Map</span>&nbsp;
                    <span class="badge badge-secondary">Sagas</span>&nbsp;
                      <div className="font-italic">Es una aplicación movil para el viajero en la cual el usuario obtiene alertas relevantes que son útiles para todo viaje</div>
                    </div>
              </div>
            </div>
            <div className="carousel-item" data-interval="10000">
              <div className="row">
                    <div data-aos="zoom-in-up" className="col-sm-4 col-12">
                      <img src="/images/proj_4_2.png" style={{border: 0, backgroundColor: 'gray', height: '100vh'}}  class="img-thumbnail"></img>
                    </div>
                    <div data-aos="zoom-in" className="col-sm-8 col-12 pt-5">
                      <div className="h2">Sprint</div>
                    <span class="badge badge-secondary">React Native</span>&nbsp;
                    <span class="badge badge-secondary">Laravel</span>&nbsp;
                    <span class="badge badge-secondary">Barcode</span>&nbsp;
                    <span class="badge badge-secondary">React JS</span>&nbsp;
                    <span class="badge badge-secondary">Bootstrap</span>&nbsp;
                    <span class="badge badge-secondary">Redux</span>&nbsp;
                    <span class="badge badge-secondary">Sagas</span>&nbsp;
                    <span class="badge badge-secondary">Expo</span>&nbsp;
                    
                      <img src="/images/proj_4_1.png"  style={{border: 0, backgroundColor: 'gray', height: '50vh', float: 'right'}}  class="img-thumbnail"></img>
                      <div className="font-italic">
                        Sprint es un proyecto cuyo resultado son dos 3 sistemas, un sistema Web y dos móviles, uno para para plataformas <i>IOS</i>, y otro para <i>Android</i>.
                        El Sistema web es el encargado en la logistica de paqueteria, este cuenta con 5 roles, uno público para consulta de 
                        cualquier paquete apartir de su código y  4 roles privados:
                        El rol <b>Cliente</b>, mediante el sistema puede cargar paquetes y generar ordenes de retiro. La carga de paquetes  
                        resulta amigable por el hechoo de poder cargar los mismos tanto apartir de una interfaz gráfica como también apartir de un archivo Excel.
                        El rol <b>Administrador</b>, es reesponsable de validar los paquetes cargados por el cliente y generar planillas de recorrido
                        para los repartidores, el rol <b>Repartidor</b>, alimenta a la aplicación móvil actualizando el estados de cada orden de recorrido, 
                        orden de retiro y paquete. La Aplicación Móvil es útil para el repartidor de paquetes,
                        es atravez de la misma que el encargado de repartos sabe que paquete debe entregar
                        o recojer y a adónde. Al mismo tiempo, la aplicación genera información relevante que es
                        recolectada por el sistema web, de esta forma los interesados tienen seguimiento de los paquetes
                        todo el tiempo apartir de la lectura de codigos QR y de las firmas registradas por la aplicación.
                        Por último, el rol<b>Super usuario</b>, el cual es capaz de obtener indicadores, agregar usuarios
                         y algunas funcionalidades propias de un super administrador.
                      </div>
                    </div>
              </div>
            </div>

            <div className="carousel-item" data-interval="10000">
                <div className="row">
                      <div data-aos="zoom-in" className="col-sm-8 col-12 pt-5">
                        <div className="title text-left">Prego</div>
                    <span class="badge badge-secondary">React Native</span>&nbsp;
                    <span class="badge badge-secondary">Laravel</span>&nbsp;
                    <span class="badge badge-secondary">React JS</span>&nbsp;
                    <span class="badge badge-secondary">Bootstrap</span>&nbsp;
                    <span class="badge badge-secondary">Redux</span>&nbsp;
                    <span class="badge badge-secondary">Sagas</span>&nbsp;
                    <span class="badge badge-secondary">Landing Page</span>&nbsp;
                    <span class="badge badge-secondary">Expo</span>&nbsp;
                    <span class="badge badge-secondary">Frigma</span>&nbsp;
                        <img src="/images/proj_5_1.png" style={{border: 0, backgroundColor: 'gray', height: '50vh', float: 'right'}}   class="img-thumbnail"></img>
                        <div className="font-italic text">
                          Prego es un sistema para conectar la oferta de profesionales con la demanda de trabajos,
                          es asi que mediante una aplicación móvil uno puede registrarse
                          como aspirante a profesional o como un cliente, en caso de este último el perfil 
                          es papaz de crear trabajo para que los profesionales posteriormente los presupuesten
                          y también tener contacto con profesionales especializados, en caso de ser profesional, 
                          una vez que el perfil es evaluado, un profesional aceptado será capaz de proponer 
                          presupuestos para distintos trabajos de su rubro.
                          El sistema web posee una Landing page y un tablero de trabajo tanto para aprobar solicitudes
                          de aspirante a profesional dentro del sistema como también para visualizar trabajos, modificar
                            rubros, validar perfiles, etc.
                        </div>
                      </div>
                      <div data-aos="zoom-in-up" className="col-sm-4 col-12 box_image">
                        <img src="/images/proj_5_2.png" style={{border: 0, backgroundColor: 'gray', height: '90vh'}}   class="img-thumbnail"></img>
                      </div>
                </div>
              </div>
            <div className="carousel-item" data-interval="5000">
                <div className="row">
                      <div data-aos="zoom-in" className="col-sm-8 col-12 pt-5">
                        <div className="title text-left">Moveler</div>
                        <span class="badge badge-secondary">React Native</span>&nbsp;
                        <span class="badge badge-secondary">Laravel</span>&nbsp;
                        <span class="badge badge-secondary">React JS</span>&nbsp;
                        <span class="badge badge-secondary">Redux</span>&nbsp;
                        <span class="badge badge-secondary">Bootstrap</span>&nbsp;
                        <span class="badge badge-secondary">Sagas</span>&nbsp;
                        <span class="badge badge-secondary">Expo</span>&nbsp;
                        <span class="badge badge-secondary">Frigma</span>&nbsp;
                        <img src="/images/proj_6_1.png" style={{border: 0, backgroundColor: 'gray', height: '50vh', float: 'right'}}   class="img-thumbnail"></img>
                        <div className="font-italic">
                          Moveler es un sistema Web que alimenta una aplicación, el sistema Web es un Gestor de contenidos, dicho contenido es consumido y modificado por
                          su aplicación móvil, la cual se ejecuta en plataformas Andoid y IOS. Es atravez de esta última que el usuario puede obtener información 
                          de interes acerca de peliculas. Uno atravez de la misma puede cargar contenido propio el cual es evaluado, y una vez aprobado publicado. La aplicación 
                          movil es un blog para los viajeros amantes de peliculas.
                        </div>
                      </div>
                      <div data-aos="zoom-in-up" className="col-sm-4 col-12">
                        <img src="/images/proj_6_2.png" style={{border: 0, backgroundColor: 'gray', height: '100vh'}}  class="img-thumbnail"></img>
                      </div>
                </div>
              </div>
              <div className="carousel-item" data-interval="10000">
                <div data-aos="zoom-in" className="row">
                      <div className="col-sm-4 col-12 pt-5">
                        <div className="title">Estoker</div>
                        <span class="badge badge-secondary">Laravel</span>&nbsp;
                        <span class="badge badge-secondary">React JS</span>&nbsp;
                        <span class="badge badge-secondary">Bootstrap</span>&nbsp;
                        <span class="badge badge-secondary">Frigma</span>&nbsp;
                        <div className="font-italic text">
                        Atravez del sistema uno puede cargar productos tanto mediante un archivo Excel como por interfaz gráfica de usuario.
                        Tiene una interfaz amigable para tener toda la información relevante de mi stock, entradas y salidas de forma sencilla y resumida.
                        Mediante el sistema el usario administrador es capaz deregistrar ventas, registrar pagos, obtener indicadores, etc.
                        El sistema tiene 3 roles, uno publico el cual uno puede saber la disponibilidad de un producto
                        y dos privados, uno para poder registrar ventas y otro para tener un control de toda la información registrada, esto es
                        inventario, egreso, ingresos, etc.
                        </div>
                      </div>
                      <div data-aos="zoom-in-up" className="col-sm-7 col-12 box_image">
                      <img src="/images/proj_7.png" style={{border: 0, backgroundColor: 'gray', height: '70vh'}}   class="img-thumbnail"></img>
                      </div>
                </div>
              </div>
              <div className="carousel-item" data-interval="10000">
                <div className="row">
                      <div data-aos="zoom-in" className="col-sm-4 col-12 pt-5">
                        <div className="title">Seccoplac</div>
                        <span class="badge badge-secondary">Laravel</span>&nbsp;
                        <span class="badge badge-secondary">React JS</span>&nbsp;
                        <span class="badge badge-secondary">Bootstrap</span>&nbsp;
                        <span class="badge badge-secondary">Landing Page</span>&nbsp;
                        <div className="font-italic text">Es el sitio web de la empresa reconocida Seccoplac, la misma es útil para la recolección de nuevos clientes, nuevos franquiciados y mostrar sus productos. Posee de un chat bot integrado.</div>
                      </div>
                      <div data-aos="zoom-in-up" className="col-sm-8 col-12">
                        <img src="/images/proj_3.png" style={{border: 0, height: '70vh',backgroundColor: 'gray'}}  width="700" height="400"  class="img-thumbnail"></img>
                      </div>
                </div>
              </div> 
          </div>
          <a className="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
            <span style={{position: 'absolute',top: '0px'}} className="carousel-control-prev-icon" aria-hidden="true"></span>
            <span className="sr-only">Previous</span>
          </a>
          <a className="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
            <span style={{position: 'absolute',top: '0px'}} className="carousel-control-next-icon" aria-hidden="true"></span>
            <span className="sr-only">Next</span>
          </a>
        </div>
                  
      </div>
    </section>
    )
};