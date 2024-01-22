import React, { useState } from 'react';
import './Signin.css';
import emailIcon from '../Assets/email.png';
import Resetpassword from './Resetpassword';

const Forgetpassword = ({ onBackToLogin, onVerifySuccess }) => {
  const [isVerification, setIsVerification] = useState(false);
  const [verificationCode, setVerificationCode] = useState('');

  const handleContinue = () => {
    // logic to send the verification code to the email
    // assume the code is sent successfully
    setIsVerification(true);
  };

  const handleVerify = () => {
    onVerifySuccess();
  };

  const handleBack = () => {
    setIsVerification(false);
  };

  return (
    <div className='signin-container'>
      <div className="forget-header">
        <div className="signin-text">Forgot Password</div>
      </div>
      {!isVerification && (
        <div>
          <div className='message'>
            Enter your email for the verification process, we will send a code to your email.
          </div>
          <div className="signin-inputs">
            <div className="signin-txts">Email</div>
            <div className="signin-input">
              <img src={emailIcon} alt="" />
              <input type="email" />
            </div>
            <div className="forget-submit" onClick={handleContinue}>
              Continue
            </div>
          </div>
          <div className="back-to-login" onClick={onBackToLogin}>
            <div className="signin-new-user">
              <span>Back to Login</span>
            </div>
          </div>
        </div>
      )}

      {isVerification && (
        <div>
          <div className='message'>
            Paste the code we sent to the email you entered to the box below.
          </div>
          <div className="signin-inputs">
            <div className="signin-input">
              <input
                type="text"
                placeholder="Paste here"
                value={verificationCode}
                onChange={(e) => setVerificationCode(e.target.value)}
                style={{ marginLeft: '10px' }}
              />
            </div>
            <div className="forget-submit" onClick={handleVerify}>
              Verify
            </div>
          </div>
          {/* Conditionally render Resetpassword component */}
          {isVerification && <Resetpassword />}
          <div className="back-to-login" onClick={handleBack}>
            <div className="signin-new-user">
              <span>Back to Login</span>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default Forgetpassword;
