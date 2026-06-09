<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pogoda PPG - Czerwonak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-md text-center border border-gray-700">
        <h1 class="text-2xl font-bold text-gray-400 mb-2">🚀 Status Lotu (PPG)</h1>
        <p class="text-sm text-gray-500 mb-6">Startowisko: Czerwonak / Ławica</p>

        <div id="loading" class="animate-pulse text-yellow-400 font-semibold text-lg">
            Pobieranie najnowszych danych meteo...
        </div>

        <div id="weather-card" class="hidden">
            <div id="status-badge" class="inline-block px-8 py-3 rounded-full text-4xl font-black tracking-widest mb-6">
                --
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-700 p-4 rounded-xl">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Średni Wiatr</p>
                    <p id="avg-wind" class="text-3xl font-bold text-white mt-1">-- <span class="text-lg font-normal text-gray-400">m/s</span></p>
                </div>
                <div class="bg-gray-700 p-4 rounded-xl">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Decyzja</p>
                    <p id="is-safe" class="text-xl font-bold text-white mt-2">--</p>
                </div>
            </div>

            <div class="text-left bg-gray-900 p-4 rounded-xl text-sm text-gray-400 border border-gray-700">
                <div class="flex justify-between mb-2 border-b border-gray-700 pb-2">
                    <span>🌍 Open-Meteo:</span>
                    <span id="meteo-data" class="font-mono text-white">--</span>
                </div>
                <div class="flex justify-between">
                    <span>✈️ METAR (EPPO):</span>
                    <span id="metar-data" class="font-mono text-white">--</span>
                </div>
            </div>

            <div id="warning-box" class="hidden mt-4 p-3 bg-red-900/50 border border-red-500 rounded-xl text-red-400 text-sm font-semibold">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch('/api/conditions')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading').classList.add('hidden');
                    document.getElementById('weather-card').classList.remove('hidden');

                    const badge = document.getElementById('status-badge');
                    badge.innerText = data.status;
                    
                    if (data.is_safe_to_fly) {
                        badge.classList.add('bg-green-500', 'text-green-900');
                        document.getElementById('is-safe').innerText = "BEZPIECZNIE";
                        document.getElementById('is-safe').classList.add('text-green-400');
                    } else {
                        badge.classList.add('bg-red-500', 'text-red-900');
                        document.getElementById('is-safe').innerText = "NIEBEZPIECZNIE";
                        document.getElementById('is-safe').classList.add('text-red-400');
                    }

                    document.getElementById('avg-wind').innerHTML = `${data.average_wind_ms} <span class="text-lg font-normal text-gray-400">m/s</span>`;
                    document.getElementById('meteo-data').innerText = `${data.details.open_meteo.wind_speed_ms} m/s (Kierunek: ${data.details.open_meteo.direction_deg}°)`;
                    document.getElementById('metar-data').innerText = `${data.details.metar_eppo.wind_speed_ms} m/s (Kierunek: ${data.details.metar_eppo.direction_deg}°)`;

                    if (data.warning) {
                        const warningBox = document.getElementById('warning-box');
                        warningBox.innerText = data.warning;
                        warningBox.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    document.getElementById('loading').innerText = "Błąd połączenia z serwerem API.";
                    document.getElementById('loading').classList.add('text-red-500');
                    console.error("API Error:", error);
                });
        });
    </script>
</body>
</html>