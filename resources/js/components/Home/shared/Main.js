import React from 'react';
import bg from "../assets/bg3.jpg"
import Image from '../assets/logo.png'

// translate3d(-450px,-140px,0) scale(0.2)

 const Main = (props) => {
  return (
      <div  >
          {props.children}
      </div>
  )
};
export default Main