import React, { useEffect, useState } from 'react';

const SettingsPage = () => {
  const [formData, setFormData] = useState({
    googleApiKey: 'dfdsfsdfsdfsdfsdf',
    language: 'en',
    clearLog: false,
  });

  const [status, setStatus] = useState('');

  useEffect(() => {
    // Ensure gapSettingsData exists
    if (!window.gapSettingsData) {
      console.error('gapSettingsData is not defined.');
      setStatus('Error: Configuration data is not available.');
      return;
    }

    // Fetch existing settings
    const fetchSettings = async () => {
      try {
        const response = await fetch(window.gapSettingsData.restUrl, {
          method: 'GET',
          headers: {
            'X-WP-Nonce': window.gapSettingsData.nonce,
          },
        });
        const data = await response.json();
        setFormData(data);
      } catch (error) {
        console.error('Error fetching settings:', error);
        setStatus('Error fetching settings.');
      }
    };

    fetchSettings();
  }, []);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value, type, checked } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value,
    }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!window.gapSettingsData) {
      setStatus('Error: Configuration data is not available.');
      return;
    }

    try {
      const response = await fetch(window.gapSettingsData.restUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': window.gapSettingsData.nonce,
        },
        body: JSON.stringify(formData),
      });

      if (response.ok) {
        setStatus('Settings saved successfully.');
      } else {
        setStatus('Failed to save settings.');
      }
    } catch (error) {
      console.error('Error saving settings:', error);
      setStatus('An error occurred.');
    }
  };

  return (
    <div className="p-4 bg-white shadow rounded">
      <h2 className="text-lg font-semibold mb-4">Autocomplete Settings</h2>
      {status && <p className="text-green-500 mb-2">{status}</p>}
      <div>
        <a
          href="https://console.developers.google.com/"
          target="_blank"
          rel="noopener noreferrer"
          className="text-blue-500 underline"
        >
          Google Developer Console
        </a>{' '}
        to create your own api key.
      </div>

      <form onSubmit={handleSubmit}>
        <div className="mb-4">
          <label className="block mb-2">Google API Key:</label>
          <input
            type="password"
            name="googleApiKey"
            value={formData.googleApiKey}
            onChange={handleInputChange}
            className="border p-2 w-full"
          />
        </div>
        <div className="mb-4">
          <label className="block mb-2">Language:</label>
          <input
            type="text"
            name="language"
            value={formData.language}
            onChange={handleInputChange}
            className="border p-2 w-full"
          />
        </div>
        {/* <div className="mb-4 flex items-center">
          <label className="mr-2">Clear Log:</label>
          <input
            type="checkbox"
            name="clearLog"
            checked={formData.clearLog}
            onChange={handleInputChange}
          />
        </div> */}
        <button type="submit" className="button button-primary">
          Save Settings
        </button>
      </form>
    </div>
  );
};

export default SettingsPage;
