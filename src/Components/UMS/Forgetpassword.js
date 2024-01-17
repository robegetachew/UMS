// Forgetpassword.js
import React, { useState } from 'react';

const Forgetpassword = () => {
  const [email, setEmail] = useState('');
  const [isPasswordResetSent, setPasswordResetSent] = useState(false);

  const handleEmailChange = (event) => {
    setEmail(event.target.value);
  };

  const handlePasswordReset = () => {
    // Add your logic for sending a password reset email
    // For demonstration purposes, just console log the email
    console.log(`Password reset email sent to: ${email}`);

    // Update state to indicate that password reset email is sent
    setPasswordResetSent(true);
  };

  return (
    <div>
        <div className='signin-container'>
      <h2>Forget Password</h2>
      {isPasswordResetSent ? (
        <div>
          <p>Password reset instructions sent to your email.</p>
          {/* You can add additional styling or links here */}
        </div>
      ) : (
        <div>
          <p>
            Please enter your email address. We will send you instructions on
            how to reset your password.
          </p>
          <form>
            <label>Email:</label>
            <input
              type="email"
              value={email}
              onChange={handleEmailChange}
              required
            />
            <button type="button" onClick={handlePasswordReset}>
              Reset Password
            </button>
          </form>
        </div>
      )}
    </div>
    </div>
  );
};

export default Forgetpassword;
