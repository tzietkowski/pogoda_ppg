# 🪂 Pogoda PPG - Weather API

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Sail-2496ED?style=flat-square&logo=docker&logoColor=white)

A backend API service built with **Laravel**, designed to evaluate and analyze weather conditions for paramotoring (PPG) flights in the Czerwonak/Poznań area. 

The application aggregates meteorological data from various external providers, processes it through a custom evaluation engine, and exposes clear, flight-readiness metrics via RESTful endpoints.

## 🎯 Key Features & Architecture
This project is built with a strong focus on clean code and modern PHP architecture:
* **External API Integrations:** Fetches real-time data from Open-Meteo and aviation METAR stations (EPPO).
* **Object-Oriented Design (OOP):** Heavily utilizes Interfaces, Abstract Classes, and Traits to ensure modularity.
* **Dependency Injection:** External providers and internal services are decoupled and injected, adhering to **SOLID** principles.
* **Robust Error Handling:** Comprehensive `try-catch` blocks and custom exception management for unreliable external networks.

## 🛠️ Tech Stack
* **Language:** PHP 8.x (Strict Types)
* **Framework:** Laravel
* **Environment:** Docker (Laravel Sail)
* **HTTP Client:** Guzzle

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

## 📡 API Endpoints
(To be updated as the project grows)