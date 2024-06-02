import React from 'react';
import Header from './Header';
import Footer from './Footer'
import {LanguageContext, language} from '../LanguagesContext';




function Layout(props) {
  const [lang,setLang] = React.useState(language.es)
  const changeLang = () => {
    if (lang===language.es){
      setLang(language.en)
    }else{
      setLang(language.es)
    }
  }
  return(
        <LanguageContext.Provider value={lang} >
          {/* <Header pathname={props.pathname} /> */}
            {props.children}
            {/* <div onClick={changeLang}  className="spanish"  >
              <LanguageContext.Consumer>
                {({name}) => {
                  return <div>{name}</div>
                }}
              </LanguageContext.Consumer>
            </div> */}
          </LanguageContext.Provider>
  )
}

export default (Layout);
