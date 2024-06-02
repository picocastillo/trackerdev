import React from 'react';
import {Link } from "react-router-dom";
import Image from '../assets/logo.png'

import {LanguageContext} from '../LanguagesContext';




function Header({pathname}) {
  return(
    <div id="header">
      <nav className=" my_header navbar navbar-expand-lg  navbar-light z_index">
        {/* <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
          <span className="navbar-toggler-icon"></span>
        </button> */}
      
        {/* <div className="collapse navbar-collapse justify mt-lg-5" > */}
          
        <a className="navbar-brand item_menu text-white" href="/login">
        <LanguageContext.Consumer>
          {({menu}) => {
            return <div>{menu.login}</div>
          }}
      </LanguageContext.Consumer>
          
          </a>
          <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span className="navbar-toggler-icon"></span>
          </button>
          <div className=" nav_right collapse navbar-collapse" id="navbarNavAltMarkup">
            {/* <div className="navbar-nav">
              <div className={`big_text ${pathname==='/' ? 'my_underline menu_active' : 'my_underline'}`}>
                <div className=" mx-5">
                  <Link to='/' className="nav-item nav-link text-white " >
                    <LanguageContext.Consumer>
                        {({menu}) => {
                          return <div>{menu.home}</div>
                        }}
                    </LanguageContext.Consumer>
                    </Link>
                </div>
              </div>
              <div className={`big_text ${pathname==='/methodology' ? 'my_underline menu_active' : 'my_underline'}`}>
                <div className=" mx-5">
                  <Link to='/methodology' className="nav-item nav-link text-white " >
                  <LanguageContext.Consumer>
                      {({menu}) => {
                        return <div>{menu.methodology}</div>
                      }}
                  </LanguageContext.Consumer>
                  </Link>
                </div>
              </div>
              <div className={`big_text ${pathname==='/projects' ? 'my_underline menu_active' : 'my_underline'}`}>
                <div className=" mx-5">
                  <Link to='/projects' className="nav-item nav-link text-white " >
                  <LanguageContext.Consumer>
                      {({menu}) => {
                        return <div>{menu.projects}</div>
                      }}
                  </LanguageContext.Consumer>
                  </Link>
                </div>
              </div>
              <div className={`big_text ${pathname==='/contact' ? 'my_underline menu_active' : 'my_underline'}`}>
                <div className=" mx-5">
                  <Link to='/contact' className="nav-item nav-link text-white " >
                  <LanguageContext.Consumer>
                      {({menu}) => {
                        return <div>{menu.contact}</div>
                      }}
                  </LanguageContext.Consumer>
                  </Link>
                </div>
              </div>
              
            </div> */}
      </div>
          
          
          
          
          {/* <ul className="navbar-nav my_menu mr-auto mt-2 mt-lg-5">
            <div className={pathname==='/' ? 'my_underline menu_active' : 'my_underline'}>
              <li className="nav-item hover_big_size mx-5">
               <Link to='/' className="nav-link text-white item_menu" >Inicio</Link>
              </li>
            </div>
            <div className={pathname==='/methodology' ? 'my_underline menu_active' : 'my_underline'}>
              <li className="nav-item hover_big_size  mx-5">
               <Link to='/methodology' className="nav-link text-white item_menu" >Metodologia</Link>
              </li>
            </div>
            <div className={pathname==='/projects' ? 'my_underline menu_active' : 'my_underline'}>
              <li className="nav-item hover_big_size  mx-5">
                <Link to='/projects' className="nav-link text-white item_menu" >Porfolio</Link>
              </li>
            </div>
            <div className={pathname==='/contact' ? 'my_underline menu_active' : 'my_underline'}>
              <li className="nav-item hover_big_size  mx-5">
                <Link to='/contact' className="nav-link text-white item_menu" >Contacto</Link>
              </li>
            </div>
          </ul> */}
        {/* </div> */}
        {/* <div >
          <a style={styles.login} className="item_menu" href="/login" >INGRESAR</a>
        </div> */}
      </nav>
      
    </div>
  )
}

export default Header;

const styles = {
  login: {
    color: 'white',
    fontWeight: 'bold',
    fontFamily: 'monospace',
    fontSize: '1.3wh',
    textDecoration: 'none'

  }
}