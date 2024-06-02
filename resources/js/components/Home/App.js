import React from 'react';
import {
  BrowserRouter,
  Switch,
  Route,
  // Link
} from 'react-router-dom';
import ReactDOM from 'react-dom';

import { CSSTransition, TransitionGroup } from "react-transition-group";

// import 'bootstrap/dist/css/bootstrap.min.css';
// import 'bootstrap/dist/js/bootstrap.bundle.min';

import './App.css';

import Home from './pages/Home';
import Methodology from './pages/Methodology';
import Projects from './pages/Projects';
import Contact from './pages/Contact';
import Layout from './shared/Layout';


const NotFound = () =>{
  return (
    <div>
      <h1 className="text-white text-center">No existe esa URL</h1>
    </div>
  )
}

function App(props) {
    return (
      <BrowserRouter>
      <div className="app ">
        <Route render={(location) => {
            return (
              <Layout pathname={location.location.pathname}>
                <TransitionGroup component={null}>
                  <CSSTransition
                  key={location.key} timeout={0.5} 
                  >
                      <Switch location={location.key}>
                        <Route exact path="/" component={Home} />
                        <Route exact path="/methodology" component={Methodology} />
                        <Route exact path="/projects" component={Projects} />
                        <Route exact path="/contact" render={ () => <Contact token={props.token} />} />
                        <Route  component={NotFound} />
                      </Switch>
                  </CSSTransition>
                </TransitionGroup>
              </Layout>
            )}
        }
      />
      </div>
      </BrowserRouter>
    );
}
export default App;


if (document.getElementById('app-home')) {
    const token = document.getElementById('app-home').getAttribute('token')
    ReactDOM.render(<App token={token} />, document.getElementById('app-home'));
}
