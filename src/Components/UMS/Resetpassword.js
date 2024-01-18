import React, { useState } from 'react';
import './Signin.css'; 
import passwordIcon from '../Assets/password.png';

const ResetPassword = ({ onBackToLogin }) => {
  const [isWrongPassword, setIsWrongPassword] = useState(false);

  const handleResetPassword = () => {
//assume some logic to check if the new password is valid
    const isNewPasswordInvalid = true; 

    if (isNewPasswordInvalid) {
      setIsWrongPassword(true);
      // display an error message
    } else {
      // handle successful password reset
      //let's assume it navigate back to the login page
      onBackToLogin();
    }
  };

  return (
    <div className='signin-container'>
      <div className="signin-header">
        <div className="signin-text">Reset Password</div>
      </div>
      <div className="signin-inputs">
        <div className="signin-txts">New Password</div>
        <div className="signin-input">
          <img src={passwordIcon} alt="" />
          <input type="password" />
        </div>
        <div className="signin-txts">Confirm Password</div>
        <div className="signin-input">
          <img src={passwordIcon} alt="" />
          <input type="password" />
        </div>
        <div className="signin-submit" onClick={handleResetPassword}>
          {isWrongPassword ? "Invalid Password" : "Reset Password"}
        </div>
        <div className="signin-new-user">
          <span onClick={onBackToLogin}> Back to Login</span>
        </div>
      </div>
    </div>
  );
}

export default ResetPassword;
