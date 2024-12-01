document.addEventListener('DOMContentLoaded', () => {
    const lookupButton = document.getElementById('lookup');
    const lookupCitiesButton = document.getElementById('lookupCities');
    const resultDiv = document.getElementById('result');

    // Lookup Country Button
    lookupButton.addEventListener('click', () => {
        const countryInput = document.getElementById('country').value;
        const url = `world.php?country=${encodeURIComponent(countryInput)}`;

        // Fetch data for countries
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                resultDiv.innerHTML = data;
            })
            .catch(error => {
                console.error('Fetch error:', error);
                resultDiv.innerHTML = '<p>Error fetching data.</p>';
            });
    });

    // Lookup Cities Button
    lookupCitiesButton.addEventListener('click', () => {
        const countryInput = document.getElementById('country').value;
        const url = `world.php?country=${encodeURIComponent(countryInput)}&lookup=cities`;

        // Fetch data for cities
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                resultDiv.innerHTML = data;
            })
            .catch(error => {
                console.error('Fetch error:', error);
                resultDiv.innerHTML = '<p>Error fetching data.</p>';
            });
    });
});
