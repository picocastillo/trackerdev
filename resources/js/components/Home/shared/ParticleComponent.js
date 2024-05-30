import React from "react";
import Particles from 'react-particles-js';
//based on https://codesandbox.io/s/4k5z9xx0w?file=/src/index.js:235-608

export default () => (
  <div
    style={{
      position: "absolute",
      top: 0,
      left: 0,
      width: "100%",
      height: "100%"
    }}
  >
    <Particles
      params={{
        "particles": {
            "number": {
                "value": 50,
            },
            "size": {
                "value": 3
            },
            line_linked: {
              shadow: {
                enable: true,
                color: "#3CA9D1",
                blur: 5
              }
            }
        },
        "interactivity": {
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "repulse"
                }
            }
        }
    }}
    style={{
        width: '100%',
        width: '100%',
        backgroundImage: `url('/images/bg3.jpg') `,
        backgroundImage: `linear-gradient(rgb(153 45 0 / 67%) 0%, #1b1e21 100%), url(/images/bg3.jpg),url('/images/bg3.jpg') `,
        backgroundSize: 'cover' 
      }}
    />
  </div>
);
