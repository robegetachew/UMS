import React, { useState } from 'react';
import './Registration.css';
import personIcon from '../Assets/person.png';
import emailIcon from '../Assets/email.png';
import passwordIcon from '../Assets/password.png';

const Registration = ({ onToggle, onCreateAccount }) => {
  const [password, setPassword] = useState('');
  const [passwordStrength, setPasswordStrength] = useState('');

  const handlePasswordChange = (event) => {
    const newPassword = event.target.value;
    setPassword(newPassword);
    const strength = calculatePasswordStrength(newPassword);
    setPasswordStrength(strength);
  };

  const calculatePasswordStrength = (password) => {
   
    if (password.length < 6) {
      return 'Weak';
    } else if (password.length < 10) {
      return 'Moderate';
    } else {
      return 'Strong';
    }
  };

  return (
    <div className='reg-container'>
      <div className="reg-header">
        <div className="reg-text">Create an account</div>
      </div>
      <div className="reg-inputs">
        <div className="reg-txts">Email</div>
        <div className="reg-input">
          <img src={emailIcon} alt="" />
          <input type="email" />
        </div>
        <div className="reg-txts">Username</div>
        <div className="reg-input">
          <img src={personIcon} alt="" />
          <input type="text" />
        </div>
        <div className="reg-txts">Password</div>
        <div className="reg-input">
          <img src={passwordIcon} alt="" />
          <input
            type="password"
            value={password}
            onChange={handlePasswordChange}
          />
          {passwordStrength && (
            <div className={`password-strength ${passwordStrength.toLowerCase()}`}>
              {passwordStrength} Password
            </div>
          )}
        </div>
        <div className="reg-txts">Confirm Password</div>
        <div className="reg-input">
          <img src={passwordIcon} alt="" />
          <input type="password" />
        </div>
      </div>
      <div className="reg-submit" onClick={onCreateAccount}>Create account</div>
      <div className="reg-have-account">Already a user?<span onClick={onToggle}> Sign in</span></div>
      <div className="reg-submit-container"></div>
    </div>
  );
}

export default Registration;
