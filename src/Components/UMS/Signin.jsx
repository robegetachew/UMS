//Signin.jsx

import React from 'react';
import './Signin.css';  // Import the correct CSS file
import emailIcon from '../Assets/email.png';
import passwordIcon from '../Assets/password.png';

const Signin = ({ onToggle }) => {
  return (
    <div className='signin-container'>
      <div className="signin-header">
        <div className="signin-text">Log in</div>
      </div>
      <div className="signin-inputs">
        <div className="signin-txts">Email</div>
        <div className="signin-input">
          <img src={emailIcon} alt="" />
          <input type="signin-email" />
        </div>
        <div className="signin-txts">Password</div>
        <div className="signin-input">
          <img src={passwordIcon} alt="" />
          <input type="signin-password" />
        </div>
        <div className="signin-submit" onClick={onToggle}>Log in</div>
      <div className="signin-forget"><span> Forget password?</span></div>
      <div className="signin-new-user">New User? <span onClick={onToggle}> Sign Up</span></div>
      </div>
    </div>
  );
}

export default Signin;
