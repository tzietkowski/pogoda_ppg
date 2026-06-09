# 🪂 Pogoda PPG - Weather API

[![CI/CD Pipeline](https://github.com/tzietkowski/pogoda_ppg/actions/workflows/run-tests.yml/badge.svg)](https://github.com/tzietkowski/pogoda_ppg/actions)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Sail-2496ED?style=flat-square&logo=docker&logoColor=white)
![PHPUnit](https://img.shields.io/badge/Testing-PHPUnit-4A5B9D?style=flat-square&logo=phpunit&logoColor=white)

A backend API service built with **Laravel**, designed to evaluate and analyze weather conditions for paramotoring (PPG) flights in the Czerwonak/Poznań area. 

## 📸 Application Preview
![Dashboard PPG](screenshots/dashboard.png)

The application aggregates meteorological data from various external providers, processes it through a custom evaluation engine, and exposes clear, flight-readiness metrics via RESTful endpoints. It also maintains a historical log of weather conditions in a database for future trend analysis.

## 🎯 Key Features & Architecture
This project is built with a strong focus on clean code and modern PHP architecture:
* **Dynamic Spot Management (CRUD):** A fully validated RESTful resource for managing takeoff locations, strictly verified against a whitelist of operational Polish METAR stations.
* **Route Model Binding & Custom Keys:** Seamless endpoint resolution (e.g., `/api/conditions/EPPO`) allowing intuitive API consumption.
* **Smart Data Caching (Memoization):** A custom caching layer within the abstract weather provider prevents redundant HTTP requests, mapping API responses to unique geographic coordinates and station codes.
* **External API Integrations:** Fetches real-time data from Open-Meteo and aviation METAR stations (EPPO).
* **Object-Oriented Design (OOP):** Heavily utilizes Interfaces, Abstract Classes, and polimorphism to ensure a modular data-fetching layer.
* **Database Logging (Eloquent ORM):** Automatically logs weather reports and raw API responses (stored as JSON) into MySQL using Laravel Migrations and Eloquent.
* **Automated Testing:** Fully tested using Feature and Unit tests, including simulated/mocked HTTP requests to external APIs to guarantee reliability without hitting live servers.
* **Dependency Injection:** External providers and internal services are decoupled and injected, adhering to **SOLID** principles.

## 🛠️ Tech Stack
* **Language:** PHP 8.x (Strict Types)
* **Framework:** Laravel 11.x
* **Database:** MySQL
* **Testing:** PHPUnit / Pest (Http::fake)
* **Environment:** Docker (Laravel Sail)

## 🚀 Local Setup (Docker)
This project uses Laravel Sail for a seamless, containerized development environment.

1. Clone the repository:
   ```bash
   git clone git@github.com:tzietkowski/pogoda_ppg.git
   cd pogoda_ppg

2. Install dependencies:
   ```bash
   composer install

3. Copy the environment file and generate the app key:
   ```bash
   cp .env.example .env
   ./vendor/bin/sail artisan key:generate

4. Start the Docker containers:
   ```bash
   ./vendor/bin/sail up -d

## 📡 Core API Endpoints
Flight Conditions Analysis
GET /api/conditions/{metar_code?}
Analyzes current weather data for a specific spot. If no parameter is provided, it defaults to the primary location (Czerwonak/EPPO).

Response (200 OK):
```json
{
  "spot_name": "Pruszcz Gdański - Pola",
  "status": "GO",
  "average_wind_ms": 2.5,
  "is_safe_to_fly": true,
  "warning": null,
  "details": {
    "open_meteo": {
      "wind_speed_ms": 2.3,
      "direction_deg": 180
    },
    "metar_epgd": {
      "wind_speed_ms": 2.7,
      "direction_deg": 170
    }
  }
}
```
Manage Spots
GET /api/spots
Retrieve all configured flying spots.

POST /api/spots
Add a new spot (Strictly validated for coordinates and active Polish METAR codes).

Request Payload Example:
```json
{
    "name": "Pruszcz Gdański - Pola",
    "latitude": 54.26,
    "longitude": 18.63,
    "metar_code": "EPGD"
}
```


Error Response (503 Service Unavailable):
Triggered if external weather APIs fail to respond or return invalid data.

```json
{
  "error": "Nie udało się pobrać danych pogodowych.",
  "message": "Awaria sieci: Nie udało się pobrać danych z Open-Meteo"
}
```

## 🗺️ Roadmap & Future Development
The backend API is fully operational, but the project is continuously evolving. Planned features for upcoming releases include:

* **Vertical Wind Gradient Analysis:** Expanding the evaluation engine to fetch and analyze wind speeds at various altitudes (e.g., surface, 100m, 300m). This is critical for paramotor pilots to identify potential wind shear and plan safe ascents.
* **Professional AWOS-Style Dashboard (React):** Developing a dedicated, decoupled frontend application using **React**. The goal is to build a high-contrast, full-screen dashboard inspired by professional Aviation Weather Observation Systems (AWOS). It will visualize real-time data such as wind roses relative to specific takeoff headings, pressure trends (QNH/QFE), and dew points in a pilot-friendly layout.