import React, { useState } from 'react';
import Signin from './Components/UMS/Signin';
import Registration from './Components/UMS/Registration';
import Forgetpassword from './Components/UMS/Forgetpassword';
import Resetpassword from './Components/UMS/Resetpassword';
import Wrongpassword from './Components/UMS/Wrongpassword';

const App = () => {
  const [isLogin, setIsLogin] = useState(true);
  const [isForgetPassword, setIsForgetPassword] = useState(false);
  const [isVerificationSuccess, setIsVerificationSuccess] = useState(false);

  const handleToggle = () => {
    setIsLogin(!isLogin);
    setIsForgetPassword(false);
    setIsVerificationSuccess(false);
  };

  const handleBackToLogin = () => {
    setIsLogin(true);
    setIsForgetPassword(false);
    setIsVerificationSuccess(false);
  };

  const handleForgetPassword = () => {
    setIsLogin(false);
    setIsForgetPassword(true);
    setIsVerificationSuccess(false);
  };

  const handleVerificationSuccess = () => {
    setIsVerificationSuccess(true);
  };

  const handleSignup = () => {
    setIsLogin(false);
    setIsForgetPassword(false);
    setIsVerificationSuccess(false);
  };

  return (
    <div>
      {isLogin && !isForgetPassword && !isVerificationSuccess && (
        <Signin onToggle={handleToggle} onForgetPassword={handleForgetPassword} onSignup={handleSignup} />
      )}
      {!isLogin && !isForgetPassword && !isVerificationSuccess && (
        <Registration onToggle={handleToggle} />
      )}
      {!isLogin && isForgetPassword && !isVerificationSuccess && (
        <Forgetpassword onBackToLogin={handleBackToLogin} onVerifySuccess={handleVerificationSuccess} />
      )}
      {isVerificationSuccess && (
        <Resetpassword />
      )}
      {!isLogin && !isForgetPassword && !isVerificationSuccess && (
        <Wrongpassword onToggle={handleToggle} onBackToLogin={handleBackToLogin} />
      )}
    </div>
  );
};

export default App;
