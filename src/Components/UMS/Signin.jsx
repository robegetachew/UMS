// Signin.jsx
import React, { useState } from 'react';
import './Signin.css';
import emailIcon from '../Assets/email.png';
import passwordIcon from '../Assets/password.png';

const Signin = ({ onToggle, onForgetPassword, onSignup }) => {
  const [isWrongPassword, setIsWrongPassword] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleLogin = () => {
    const apiUrl = 'http://laravelapi/login';

    if (!email || !password) {
      setIsWrongPassword(true);
      return;
    }

    const loginData = {
      email: email,
      password: password,
    };

    fetch(apiUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(loginData),
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        console.log('Login successful:', data);
        window.location.href = '../Component/UMS/Admin.jsx';
      })
      .catch(error => {
        console.error('Error during login:', error);
        setIsWrongPassword(true);
      });
  };

  return (
    <div className='signin-container'>
      <div className="signin-header">
        <div className="signin-text">Log in</div>
      </div>
      <div className="signin-inputs">
        <div className="signin-txts">Email</div>
        <div className="signin-input">
          <img src={emailIcon} alt="" />
          <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} />
        </div>
        <div className="signin-txts">Password</div>
        <div className="signin-input">
          <img src={passwordIcon} alt="" />
          <input type="password" value={password} onChange={(e) => setPassword(e.target.value)} />
        </div>
        <div className="signin-submit" onClick={handleLogin}>
          {isWrongPassword ? "Invalid" : "Log in"}
        </div>
        <div className="signin-forget" onClick={onForgetPassword}>
          <span> Forget password?</span>
        </div>
        <div className="signin-new-user">New User? <span onClick={onSignup}> Sign Up</span></div>
      </div>
    </div>
  );
}

export default Signin;
