<?php require APPROOT . '/views/inc/header.php'; ?>
<style>
    .weather-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .weather-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: transform 0.2s;
    }

    .weather-card:hover {
        transform: translateY(-5px);
    }

    .weather-icon {
        font-size: 3rem;
        margin-bottom: 10px;
        color: var(--primary-color);
    }

    .temp-large {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
    }

    .weather-detail-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }

    .weather-detail-row:last-child {
        border-bottom: none;
    }

    .alert-box {
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: none;
        /* Hidden by default */
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--primary-color);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<div class="container">
    <a href="<?php echo URLROOT; ?>/farmer/dashboard" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>

    <h2><?php echo $data['title']; ?></h2>
    <p class="text-muted" id="location-status">Detecting location...</p>

    <!-- Weather Alerts -->
    <div id="weather-alerts" class="alert-box">
        <h4><i class="fa fa-exclamation-triangle"></i> Weather Alert</h4>
        <p id="alert-message">loading...</p>
    </div>

    <!-- Loading Indicator -->
    <div id="loading" class="text-center">
        <div class="loading-spinner"></div>
        <p>Fetching weather data...</p>
    </div>

    <div id="weather-content" class="weather-container" style="display: none;">
        <!-- Current Weather -->
        <div class="weather-card">
            <h3>Current Weather</h3>
            <div id="current-icon" class="weather-icon">☀️</div>
            <div class="temp-large"><span id="current-temp">--</span>°C</div>
            <p id="current-condition">Sunny</p>
            <div class="weather-detail-row mt-3">
                <span>Wind Speed</span>
                <span id="current-wind">-- km/h</span>
            </div>
            <div class="weather-detail-row">
                <span>Humidity</span>
                <span id="current-humidity">--%</span>
            </div>
        </div>

        <!-- Today's Summary -->
        <div class="weather-card">
            <h3>Today's Summary</h3>
            <div class="weather-detail-row mt-3">
                <span>Max Temperature</span>
                <span id="today-max">--°C</span>
            </div>
            <div class="weather-detail-row">
                <span>Min Temperature</span>
                <span id="today-min">--°C</span>
            </div>
            <div class="weather-detail-row">
                <span>Sunrise</span>
                <span id="today-sunrise">--:--</span>
            </div>
            <div class="weather-detail-row">
                <span>Sunset</span>
                <span id="today-sunset">--:--</span>
            </div>
            <div class="weather-detail-row">
                <span>UV Index</span>
                <span id="today-uv">--</span>
            </div>
        </div>

        <!-- Rainfall Prediction -->
        <div class="weather-card">
            <h3>Rainfall Forecast</h3>
            <div class="weather-icon">pV</div>
            <div class="temp-large"><span id="rain-chance">--</span>%</div>
            <p>Chance of Rain</p>
            <div class="weather-detail-row mt-3">
                <span>Precipitation</span>
                <span id="rain-amount">-- mm</span>
            </div>
            <div class="weather-detail-row">
                <span>Next 3 Days</span>
                <span id="rain-trend">Loading...</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        getUserLocation();
    });

    function getUserLocation() {
        const status = document.getElementById('location-status');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    status.innerHTML = `Location detected: ${position.coords.latitude.toFixed(2)}, ${position.coords.longitude.toFixed(2)}`;
                    fetchWeather(position.coords.latitude, position.coords.longitude);
                },
                (error) => {
                    status.innerHTML = "Location denied. Using default location (New Delhi, India).";
                    // Default to New Delhi
                    fetchWeather(28.6139, 77.2090);
                }
            );
        } else {
            status.innerHTML = "Geolocation not supported. Using default location.";
            fetchWeather(28.6139, 77.2090);
        }
    }

    function fetchWeather(lat, lon) {
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,is_day,precipitation,rain,weather_code,wind_speed_10m&daily=weather_code,temperature_2m_max,temperature_2m_min,sunrise,sunset,uv_index_max,precipitation_sum,precipitation_probability_max&timezone=auto&forecast_days=3`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                displayWeather(data);
                document.getElementById('loading').style.display = 'none';
                document.getElementById('weather-content').style.display = 'grid';
            })
            .catch(error => {
                console.error('Error fetching weather:', error);
                document.getElementById('location-status').innerHTML += " (Error fetching data)";
                document.getElementById('loading').style.display = 'none';
            });
    }

    function displayWeather(data) {
        // Current
        const current = data.current;
        document.getElementById('current-temp').textContent = current.temperature_2m;
        document.getElementById('current-wind').textContent = current.wind_speed_10m + ' km/h';
        document.getElementById('current-humidity').textContent = current.relative_humidity_2m + '%';

        // Condition & Icon
        const weatherCode = current.weather_code;
        const condition = getWeatherCondition(weatherCode);
        document.getElementById('current-condition').textContent = condition.text;
        document.getElementById('current-icon').textContent = condition.icon;

        // Today's Summary
        const daily = data.daily;
        document.getElementById('today-max').textContent = daily.temperature_2m_max[0] + '°C';
        document.getElementById('today-min').textContent = daily.temperature_2m_min[0] + '°C';
        document.getElementById('today-sunrise').textContent = formatTime(daily.sunrise[0]);
        document.getElementById('today-sunset').textContent = formatTime(daily.sunset[0]);
        document.getElementById('today-uv').textContent = daily.uv_index_max[0];

        // Rainfall
        document.getElementById('rain-amount').textContent = daily.precipitation_sum[0] + ' mm';
        document.getElementById('rain-chance').textContent = daily.precipitation_probability_max[0];

        const trend = `Tom: ${daily.precipitation_probability_max[1]}%, Day after: ${daily.precipitation_probability_max[2]}%`;
        document.getElementById('rain-trend').textContent = trend;

        // Alerts (High wind, heavy rain, extreme UV)
        checkAlerts(current, daily);
    }

    function getWeatherCondition(code) {
        // WMO Weather interpretation codes (WW)
        // 0: Clear sky
        // 1, 2, 3: Mainly clear, partly cloudy, and overcast
        // 45, 48: Fog and depositing rime fog
        // 51, 53, 55: Drizzle: Light, moderate, and dense intensity
        // 61, 63, 65: Rain: Light, moderate and heavy intensity
        // 80, 81, 82: Rain showers: Slight, moderate, and violent
        // 95: Thunderstorm: Slight or moderate
        // 96, 99: Thunderstorm with slight and heavy hail

        if (code === 0) return { text: "Clear Sky", icon: "☀️" };
        if (code >= 1 && code <= 3) return { text: "Partly Cloudy", icon: "⛅" };
        if (code >= 45 && code <= 48) return { text: "Foggy", icon: "🌫️" };
        if (code >= 51 && code <= 55) return { text: "Drizzle", icon: "🌦️" };
        if (code >= 61 && code <= 65) return { text: "Rain", icon: "🌧️" };
        if (code >= 80 && code <= 82) return { text: "Showers", icon: "☔" };
        if (code >= 95) return { text: "Thunderstorm", icon: "⚡" };
        return { text: "Unknown", icon: "❓" };
    }

    function formatTime(isoString) {
        const date = new Date(isoString);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function checkAlerts(current, daily) {
        let alerts = [];

        if (current.wind_speed_10m > 30) alerts.push("High winds detected! Secure loose equipment.");
        if (daily.uv_index_max[0] > 8) alerts.push("Extreme UV Index! Limit sun exposure.");
        if (daily.precipitation_sum[0] > 20) alerts.push("Heavy rainfall expected! Prepare drainage.");
        if (daily.temperature_2m_max[0] > 40) alerts.push("Heatwave warning! Keep crops hydrated.");

        if (alerts.length > 0) {
            const alertBox = document.getElementById('weather-alerts');
            const alertMsg = document.getElementById('alert-message');
            alertMsg.innerHTML = alerts.join('<br>');
            alertBox.style.display = 'block';
        }
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>