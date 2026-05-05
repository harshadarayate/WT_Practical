import React, { useState, useEffect } from 'react';

function DigitalClock() {
  const [time, setTime] = useState(new Date());
  const [isActive, setIsActive] = useState(true);

  useEffect(() => {
    let intervalId;

    if (isActive) {
      intervalId = setInterval(() => {
        setTime(new Date());
      }, 1000);
    }

    return () => clearInterval(intervalId);
  }, [isActive]);

  const formatTime = () => {
    const hours = time.getHours().toString().padStart(2, '0');
    const minutes = time.getMinutes().toString().padStart(2, '0');
    const seconds = time.getSeconds().toString().padStart(2, '0');
    return `${hours}:${minutes}:${seconds}`;
  };

  return (
    <div style={{ textAlign: 'center', marginTop: '50px' }}>
      <h1>{formatTime()}</h1>
      <button onClick={() => setIsActive(!isActive)}>
        {isActive ? 'Stop Clock' : 'Start Clock'}
      </button>
    </div>
  );
}

export default DigitalClock;