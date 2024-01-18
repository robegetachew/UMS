import React, { useState } from 'react';
import CubeIcon from '../Assets/cube.png';
import UserProfileIcon from '../Assets/userprofile.png';
import ActivityIcon from '../Assets/activity.png';
import PasswordIcon from '../Assets/pwd.png';
import './Admin.css';

// sample
const initialUsers = [
  { id: 1, name: 'Unkwon', email: 'unknown@example.com', status: 'Active', role: 'Admin', activity: 'High' },
  { id: 2, name: 'uk1', email: 'uk1@example.com', status: 'Inactive', role: 'User', activity: 'Medium' },
];

const UserTable = ({ users, handleEdit, handleDelete }) => {
  return (
    <table className="user-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Status</th>
          <th>Role</th>
          <th>Activity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {users.map((user) => (
          <tr key={user.id}>
            <td>{user.id}</td>
            <td>{user.name}</td>
            <td>{user.email}</td>
            <td>{user.status || 'Active'}</td>
            <td>{user.role || 'User'}</td>
            <td>{user.activity || 'N/A'}</td>
            <td>
              <button onClick={() => handleEdit(user)}>Edit</button>
              <button onClick={() => handleDelete(user.id)}>Delete</button>
            </td>
          </tr>
        ))}
      </tbody>
    </table>
  );
};

const Admin = () => {
  const [activeSection, setActiveSection] = useState('Users');
  const [users, setUsers] = useState(initialUsers);
  const [selectedUser, setSelectedUser] = useState(null);

  const handleSectionChange = (section) => {
    setActiveSection(section);
  };

  const handleEdit = (user) => {
    setSelectedUser(user);
  };

  const handleDelete = (userId) => {
    // Delete user with the given ID
    setUsers(users.filter((user) => user.id !== userId));
  };

  const renderActiveSection = () => {
    switch (activeSection) {
      case 'Users':
        return (
          <div className='user-activity-container'>
            <UserTable users={users} handleEdit={handleEdit} handleDelete={handleDelete} />
          </div>
        );
      case 'UserActivity':
        return <h2>User Activity Log goes here</h2>;
      case 'Profile':
        return <h2>User Profile goes here</h2>;
      default:
        return null;
    }
  };

  return (
    <div className='whole'>
      <div className="admin-container">
        <header className="header">
          <h1>
            <span role="img" aria-label="cube icon" className="icon">
              <img src={CubeIcon} alt="Cube Icon" />
            </span>
            UMS
          </h1>
        </header>
        <div className='container'>
          <div className="text">User Management</div>
          <nav>
            <ul>
              <li
                className={`li-container ${
                  activeSection === 'Users' ? 'active' : ''
                }`}
                onClick={() => handleSectionChange('Users')}
              >
                <img src={UserProfileIcon} alt="User Icon" />
                <div className='txt'>
                  Users
                </div>
              </li>
              <li
                className={`li-container ${
                  activeSection === 'UserActivity' ? 'active' : ''
                }`}
                onClick={() => handleSectionChange('UserActivity')}
              >
                <img src={ActivityIcon} alt="Activity Icon" />
                <div className='txt'>
                  User Activity
                </div>
              </li>
              <li
                className={`li-container ${
                  activeSection === 'Profile' ? 'active' : ''
                }`}
                onClick={() => handleSectionChange('Profile')}
              >
                <img src={PasswordIcon} alt="Password Icon" />
                <div className='txt'>
                  My Profile
                </div>
              </li>
            </ul>
          </nav>
          {renderActiveSection()}
        </div>
      </div>
    </div>
  );
};
