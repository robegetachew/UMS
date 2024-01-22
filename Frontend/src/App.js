import React, { useState } from 'react';
import Registration from './Components/UMS/Registration';
import Signin from './Components/UMS/Signin';
import Forgetpassword from './Components/UMS/Forgetpassword';
import Resetpassword from './Components/UMS/Resetpassword';
import Usersetupprofile from './Components/UMS/Usersetupprofile';

// import Wrongpassword from './Components/UMS/Wrongpassword';
import { Route, Routes } from "react-router-dom";


function App() {
  return (
    <Routes>
      <Route path="/" element={<Registration/>}></Route>
      <Route path="/signin" element={<Signin/>}></Route>
      <Route path="/usersetupprofile" element={<Usersetupprofile/>}></Route>
      <Route path="/forgetpassword" element={<Forgetpassword/>}></Route>
      <Route path="/resetpassword" element={<Resetpassword/>}></Route>
    </Routes>
  );
}
// const App = () => {
//   const [view, setView] = useState('Signin');
//   const [isForgetPassword, setIsForgetPassword] = useState(false);
//   const [isVerificationSuccess, setIsVerificationSuccess] = useState(false);

//   const handleToggle = () => {
//     setView('Signin');
//     setIsForgetPassword(false);
//     setIsVerificationSuccess(false);
//   };

//   const handleBackToLogin = () => {
//     setView('Signin');
//     setIsForgetPassword(false);
//     setIsVerificationSuccess(false);
//   };

//   const handleForgetPassword = () => {
//     setView('Forgetpassword');
//     setIsForgetPassword(true);
//     setIsVerificationSuccess(false);
//   };

//   const handleVerificationSuccess = () => {
//     setIsVerificationSuccess(true);
//   };

//   const handleSignup = () => {
//     setView('Registration');
//     setIsForgetPassword(false);
//     setIsVerificationSuccess(false);
//   };

//   const handleResetPassword = () => {
//     setView('Resetpassword');
//   };

//   const renderView = () => {
//     switch (view) {
//       case 'Signin':
//         return <Signin onToggle={handleToggle} onForgetPassword={handleForgetPassword} onSignup={handleSignup} />;
//       case 'Registration':
//         return <Registration onToggle={handleToggle} />;
//       case 'Forgetpassword':
//         return <Forgetpassword onBackToLogin={handleBackToLogin} onVerifySuccess={handleVerificationSuccess} />;
//       case 'Resetpassword':
//         return <Resetpassword onBackToLogin={handleBackToLogin} />;
//       case 'Wrongpassword':
//         return <Wrongpassword onToggle={handleToggle} onBackToLogin={handleBackToLogin} />;
//       default:
//         return null;
//     }
//   };
  

//   return (
//     <div>
//       {renderView()}
//     </div>
//   );
// };

export default App;
