import React, { useState, useEffect } from 'react';
import './Usersetupprofile.css';
import person_ic from '../Assets/person.png';
import cal_ic from '../Assets/cal.png';
import select_cal_icon from '../Assets/date.png';
import location_icon from '../Assets/location.png';
import arrow_down_icon from '../Assets/arrow-down.png';
import arrow_up_icon from '../Assets/arrow-up.png';
import PhoneInput from 'react-phone-number-input';
import 'react-phone-number-input/style.css';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';
import axios from 'axios';


const Usersetupprofile = () => {
  const [fullName, setFullName] = useState('');
  const [selectedGender, setSelectedGender] = useState('');
  const [isGenderDropdownOpen, setIsGenderDropdownOpen] = useState(false);
  const [phoneNumber, setPhoneNumber] = useState('');
  const [birthdate, setBirthdate] = useState(null);
  const [countries, setCountries] = useState([]);
  const [selectedLocation, setSelectedLocation] = useState(null);
  const [isLocationDropdownOpen, setIsLocationDropdownOpen] = useState(false);
  const [isDatePickerOpen, setIsDatePickerOpen] = useState(false);
  const [selectedPicture, setSelectedPicture] = useState(null);
  const [zIndexGender, setZIndexGender] = useState(1);
  const [zIndexLocation, setZIndexLocation] = useState(1);

  useEffect(() => {
    const fetchCountries = async () => {
      try {
        const response = await axios.get('https://restcountries.com/v3.1/all');
        const countryOptions = response.data.map(country => ({
          value: country.cca2,
          label: country.name.common,
        }));
        setCountries(countryOptions);
      } catch (error) {
        console.error('Error fetching countries:', error);
      }
    };

    fetchCountries();
  }, []);

  const handleGenderButtonClick = () => {
    setIsGenderDropdownOpen(!isGenderDropdownOpen);
    setZIndexGender(isGenderDropdownOpen ? 1 : 2);
  };

  const handleGenderSelect = (gender) => {
    setSelectedGender(gender);
    setIsGenderDropdownOpen(false);
    setZIndexGender(1);
  };

  const handleDatePickerClick = () => {
    setIsLocationDropdownOpen(false);
    setIsGenderDropdownOpen(false);
    setIsDatePickerOpen(true);
  };

  const handleLocationButtonClick = () => {
    setIsLocationDropdownOpen(!isLocationDropdownOpen);
    setZIndexLocation(isLocationDropdownOpen ? 1 : 2);
  };

  const handleLocationSelect = (location) => {
    setSelectedLocation(location);
    setIsLocationDropdownOpen(false);
    setZIndexLocation(1);
  };

  const handlePictureChange = (event) => {
    const file = event.target.files[0];
    setSelectedPicture(file);
  };

  const handleFullNameChange = (event) => {
    setFullName(event.target.value);
  };

  const handleClear = () => {
    setFullName('');
    setSelectedGender('');
    setPhoneNumber('');
    setBirthdate(null);
    setSelectedLocation(null);
    setSelectedPicture(null);
  };

  const handleSaveChanges = async () => {
    // Handle saving changes to the backend (not implemented in this example)
    // Replace 'YOUR_BACKEND_API_URL' with your actual backend API URL
    console.log('Save Changes clicked. Data not sent to backend.');
  };

  return (
    <div className='container'>
      <div className="header">
        <div className="text">Finish Account Setup</div>
      </div>
      <div className="inputs">
        <div className="txts">
          Full Name
        </div>
        <div className="input">
          <img src={person_ic} alt="" />
          <input type="text" value={fullName} onChange={handleFullNameChange} />
        </div>
        <div className="txts">
          Gender
        </div>
        <div className={`input gender-input ${isGenderDropdownOpen ? 'open' : ''}`} style={{ zIndex: zIndexGender }}>
          <img src={person_ic} alt="" />
          <div className="gender-button" onClick={handleGenderButtonClick}>
            <img
              className="arrow-icon"
              src={isGenderDropdownOpen ? arrow_up_icon : arrow_down_icon}
              alt="Arrow"
            />
          </div>
          {selectedGender && (
            <div className="selected-gender">{selectedGender}</div>
          )}
          {isGenderDropdownOpen && (
            <div className="gender-dropdown">
              <div onClick={() => handleGenderSelect('Female')}>Female</div>
              <div onClick={() => handleGenderSelect('Male')}>Male</div>
            </div>
          )}
        </div>
        <div className="txts">
          Phone Number
        </div>
        <div className="input">
          <div className='c-code'>
            <PhoneInput
              placeholder="Enter phone number"
              value={phoneNumber}
              onChange={setPhoneNumber}
              defaultCountry="ET"
              countryOptions={[
                { value: 'ET', label: '+251' },
              ]}
            />
          </div>
        </div>
        <div className="txts">
          Birth Date
        </div>
        <div className="input">
          <img src={cal_ic} alt="Calendar" className="cal-icon" />
          <DatePicker
            selected={birthdate}
            onChange={(date) => setBirthdate(date)}
            dateFormat="dd/MM/yyyy"
            placeholderText="Select birth date"
            open={isDatePickerOpen}
            onClickOutside={() => setIsDatePickerOpen(false)}
          />
          <div className='select-date' onClick={handleDatePickerClick}>
            <img src={select_cal_icon} alt="Calendar" className="select-icon" />
          </div>
        </div>
        <div className="txts">
          Location
        </div>
        <div className={`input gender-input ${isLocationDropdownOpen ? 'open' : ''}`} style={{ zIndex: zIndexLocation }}>
          <img src={location_icon} alt="Location" className="location-icon" />
          <div className="gender-button" onClick={handleLocationButtonClick}>
            <img
              className="arrow-icon"
              src={isLocationDropdownOpen ? arrow_up_icon : arrow_down_icon}
              alt="Arrow"
            />
          </div>
          {selectedLocation && (
            <div className="selected-gender">{selectedLocation.label}</div>
          )}
          {isLocationDropdownOpen && (
            <div className="gender-dropdown">
              {countries.map(country => (
                <div key={country.value} onClick={() => handleLocationSelect(country)}>
                  {country.label}
                </div>
              ))}
            </div>
          )}
        </div>
        <div className="txts">
          Picture
        </div>
        <div className="input">
          <label className="upload-btn">
            <input type="file" accept="image/*" onChange={handlePictureChange} />
          </label>
        </div>
      </div>
      <div className="submit-container">
        <div className="submit" onClick={handleClear}>Clear</div>
        <div className="submitt" onClick={handleSaveChanges}>Save Changes</div>
      </div>
    </div>
  );
}

export default Usersetupprofile;
