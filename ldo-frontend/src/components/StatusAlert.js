import React, { useEffect } from 'react';

const StatusAlert = ({ type, message, onHide }) => {
  useEffect(() => {
    if (message) {
      const timer = setTimeout(() => {
        if (onHide) {
          onHide();
        }
      }, 5000);

      return () => clearTimeout(timer);
    }
  }, [message, onHide]);

  if (!message) return null;

  let alertClass = '';

  switch (type) {
    case 'success':
      alertClass = 'alert-success';
      break;
    case 'error':
      alertClass = 'alert-danger';
      break;
    default:
      alertClass = 'alert-info';
      break;
  }

  return (
    <div className={`alert ${alertClass}`}>
      {message}
    </div>
  );
};

export default StatusAlert;
